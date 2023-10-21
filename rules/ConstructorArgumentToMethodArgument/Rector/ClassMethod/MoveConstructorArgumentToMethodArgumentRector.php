<?php

declare (strict_types=1);
namespace SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**

* @see \Rector\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\MoveConstructorArgumentToMethodArgumentRectorTest
*/
final class MoveConstructorArgumentToMethodArgumentRector extends AbstractRector implements ConfigurableRectorInterface
{

    /**
     * @var string[]
     */
    private array $constructMethodParamNames = [];
    /**
     * @var MoveConstructorArgumentToMethodArgumentParameter[]
     */
    private array $replacements;

    public function __construct(private readonly BetterNodeFinder $nodeFinder)
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return require __DIR__ . '/RuleDefinition.php';
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [Class_::class, Node\Expr\PropertyFetch::class];
    }
    /**
     * @param Class_|Node\Expr\PropertyFetch $node
     */
    public function refactor(Node $node) : ?Node
    {
        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }
        elseif($node instanceof Node\Expr\PropertyFetch) {
            return $this->refactorPropertyFetch($node);
        }
        else {
            return null;
        }

    }

    private function refactorClass(Class_ $class): ?Node
    {
        $matchingClassInConfiguration = $this->findMatchingClassInConfiguration($class);

        if(!$matchingClassInConfiguration instanceof MoveConstructorArgumentToMethodArgumentParameter) {
            return null;
        }

        $constructMethod = $this->nodeFinder->findFirst($class, fn (Node $node) => $node instanceof ClassMethod && $this->isName($node->name, '__construct'));

        if(!$constructMethod instanceof ClassMethod) {
            return null;
        }

        $this->constructMethodParamNames = array_map(function (Node\Param $param) {

            if(!$param->var instanceof Node\Expr\Variable) {
                return '';
            }

            if(!is_string($param->var->name)) {
                return '';
            }

            return $param->var->name;
        }, $constructMethod->params);


        $executeMethod = $this->nodeFinder->findFirst($class,
            fn (Node $node) => $node instanceof ClassMethod && $this->isName($node->name, $matchingClassInConfiguration->methodName)
        );

        if(!$executeMethod instanceof ClassMethod) {
            return null;
        }

        $constructMethodParams = $constructMethod->params;

        $constructMethod->params = [];

        foreach ($constructMethodParams as $constructMethodParam) {
            $constructMethodParam->flags = 0;
            $executeMethod->params[] = $constructMethodParam;
        }

        return $class;
    }

    private function refactorPropertyFetch(Node\Expr\PropertyFetch $node): ?Node
    {
        if(!$this->isNames($node, $this->constructMethodParamNames)) {
            return null;
        }

        if(!$node->name instanceof Node\Identifier) {
            return null;
        }

        $name = $node->name->toString();

        return new Node\Expr\Variable($name);
    }

    /**
     * @param MoveConstructorArgumentToMethodArgumentParameter[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, MoveConstructorArgumentToMethodArgumentParameter::class);
        $this->replacements = $configuration;
    }

    private function findMatchingClassInConfiguration(Class_ $class): ?MoveConstructorArgumentToMethodArgumentParameter
    {
        foreach ($this->replacements as $replacement) {

            $classToReplace = new ObjectType($replacement->className);

            if($this->isObjectType($class, $classToReplace)){
                return $replacement;
            }

        }

        return null;
    }
}
