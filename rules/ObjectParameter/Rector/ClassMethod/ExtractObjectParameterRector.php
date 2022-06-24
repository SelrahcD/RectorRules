<?php

declare (strict_types=1);
namespace SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\NodeManipulator\ClassInsertManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Symfony\Printer\NeighbourClassLikePrinter;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**

* @see \SelrahcD\RectoRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\ExtractObjectParameterRectorTest
*/
final class ExtractObjectParameterRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ExtractObjectParameter[]
     */
    private array $extractions;

    public function __construct(
        private readonly ClassInsertManipulator $classInsertManipulator,
        private readonly NeighbourClassLikePrinter $neighbourClassLikePrinter,
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return require_once __DIR__ . '/RuleDefinition.php';
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [ClassMethod::class];
    }
    /**
     * @param ClassMethod $classMethod
     */
    public function refactor(Node $classMethod): ?Node
    {
        if (!$classMethod instanceof ClassMethod) {
            return null;
        }

        $extraction = $this->findExtraction($classMethod);

        if(!$extraction) {
            return null;
        }

        $objectParameterClass = $this->refactorClassMethod($extraction, $classMethod);

        $class = $this->betterNodeFinder->findParentType($classMethod, Class_::class);

        if (!$class instanceof Class_) {
            return null;
        }

        $namespace = $this->betterNodeFinder->findParentType($class, Node\Stmt\Namespace_::class);

        if(!$namespace instanceof Node\Stmt\Namespace_) {
            return null;
        }

        $this->addClassToNamespace($objectParameterClass, $namespace);


//        $this->nodesToAddCollector->addNodeAfterNode($objectParameterClass, $class);

        return $classMethod;
    }

    /**
     * @param ClassMethod $classMethod
     * @return array|Node\Param[]
     */
    private function convertMethodParametersToPromotedProperty(ClassMethod $classMethod): array
    {
        return array_map(function (Node\Param $param) {
            $param->flags = Class_::MODIFIER_PUBLIC + Class_::MODIFIER_READONLY;
            return $param;
        }, $classMethod->params);
    }

    /**
     * @param Variable $objectParameterVariable
     * @param Node\Param[] $constructorClassMethodParameters
     * @param ClassMethod $classMethod
     */
    private function addVariableAssignationFromParameterObjectProperties(
        Variable $objectParameterVariable,
        array $constructorClassMethodParameters,
        ClassMethod $classMethod
    ): void {
        foreach ($constructorClassMethodParameters as $param) {

            $varName = $this->getName($param->var);

            if($varName == null) {
                continue;
            }

            $assignation = new Node\Expr\Assign(
                $param->var,
                $this->nodeFactory->createPropertyFetch($objectParameterVariable, $varName)
            );

            if ($classMethod->stmts) {
                $this->nodesToAddCollector->addNodeBeforeNode($assignation, $classMethod->stmts[0]);
            }
        }
    }

    /**
     * @param ExtractObjectParameter[] $configuration
     * @return void
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ExtractObjectParameter::class);
        $this->extractions = $configuration;
    }

    private function findExtraction(ClassMethod $classMethod): ?ExtractObjectParameter
    {
        $class = $this->betterNodeFinder->findParentType($classMethod, Class_::class);

        if (!$class instanceof Class_) {
            return null;
        }

        foreach ($this->extractions as $extraction) {

            $classToExtract = new ObjectType($extraction->className);

            if(!$this->isObjectType($class, $classToExtract)){
                continue;
            }

            if (!$this->isName($classMethod, $extraction->methodName)) {
                continue;
            }

            return $extraction;
        }

        return null;
    }

    /**
     * @param ExtractObjectParameter $extraction
     * @param ClassMethod $classMethod
     * @return Class_
     */
    private function refactorClassMethod(ExtractObjectParameter $extraction, ClassMethod $classMethod): Class_
    {
        $objectParameterType = $extraction->objectParameterClassName;

        $objectParameterClass = new Class_($objectParameterType);
        $objectParameterVariableName = lcfirst($objectParameterType);

        $constructorClassMethodParameters = $this->convertMethodParametersToPromotedProperty($classMethod);

        $constructorClassMethod = $this->nodeFactory->createPublicMethod(MethodName::CONSTRUCT);
        $constructorClassMethod->params = $constructorClassMethodParameters;

        $this->classInsertManipulator->addAsFirstMethod($objectParameterClass, $constructorClassMethod);

        $objectParameterVariable = new Node\Expr\Variable($objectParameterVariableName);
        $objectParameterParam = new Node\Param(
            $objectParameterVariable,
            null,
            $objectParameterType
        );

        $classMethod->params = [
            $objectParameterParam
        ];

        $this->addVariableAssignationFromParameterObjectProperties(
            $objectParameterVariable,
            $constructorClassMethodParameters,
            $classMethod
        );
        return $objectParameterClass;
    }

    private function addClassToNamespace(Class_ $objectParameterClass, Node\Stmt\Namespace_ $namespace): void
    {
        $newNamespace = clone $namespace;
        $newNamespace->stmts[] = $objectParameterClass;

        $this->printNewNodes($objectParameterClass, $newNamespace);
    }

    private function printNewNodes(Class_ $class, Node\Stmt\Namespace_ $mainNode): void
    {
        $smartFileInfo = $this->file->getSmartFileInfo();
        $this->neighbourClassLikePrinter->printClassLike($class, $mainNode, $smartFileInfo, $this->file);
    }
}
