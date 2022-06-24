<?php

declare (strict_types=1);
namespace SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**

* @see \SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\ReplaceMethodParameterWithObjectParameterRectorTest
*/
final class ReplaceMethodParameterWithObjectParameterRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ReplaceMethodParametersWithObjectParameter[]
     */
    private array $replacements;

    public function getRuleDefinition() : RuleDefinition
    {
        return require_once __DIR__ . '/RuleDefinition.php';
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $methodCall
     */
    public function refactor(Node $methodCall): ?Node
    {
        if (!$methodCall instanceof MethodCall) {
            return null;
        }

        $replacement = $this->findReplacement($methodCall);

        if(!$replacement) {
            return null;
        }

        return $this->refactorMethodCall($replacement, $methodCall);
    }

    /**
     * @param ReplaceMethodParametersWithObjectParameter[] $configuration
     * @return void
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ReplaceMethodParametersWithObjectParameter::class);
        $this->replacements = $configuration;
    }

    private function findReplacement(MethodCall $methodCall): ?ReplaceMethodParametersWithObjectParameter
    {
        foreach ($this->replacements as $replacement) {

            $classToReplace = new ObjectType($replacement->className);

            if(! $this->isObjectType($methodCall->var, $classToReplace)){
                continue;
            }

            if (!$this->isName($methodCall->name, $replacement->methodName)) {
                continue;
            }

            return $replacement;
        }

        return null;
    }

    /**
     * @param ReplaceMethodParametersWithObjectParameter $extraction
     * @param MethodCall $methodCall
     * @return MethodCall
     */
    private function refactorMethodCall(ReplaceMethodParametersWithObjectParameter $extraction, MethodCall $methodCall): MethodCall
    {
        $parameterObject = new New_(new FullyQualified($extraction->objectParameterClassName), $methodCall->args);

        $methodCall->args = [new Node\Arg($parameterObject)];

        return $methodCall;
    }
}
