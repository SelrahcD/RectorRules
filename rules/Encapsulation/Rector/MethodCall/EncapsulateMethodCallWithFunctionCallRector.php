<?php

declare (strict_types=1);
namespace SelrahcD\RectorRules\Encapsulation\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**

* @see \SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\EncapsulateMethodCallWithFunctionCallRectorTest
*/
final class EncapsulateMethodCallWithFunctionCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var EncapsulateMethodCallWithFunctionCall[]
     */
    private array $modifications;

    public function getRuleDefinition() : RuleDefinition
    {
        return require __DIR__ . '/RuleDefinition.php';
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param MethodCall $node
     * @return Node\Expr\FuncCall|null
     */
    public function refactor(Node $node)
    {
        if(! $node instanceof MethodCall) {
            return null;
        }

        foreach ($this->modifications as $modification) {

            if(!$this->isMatch($modification, $node)) {
                continue;
            }

            return new Node\Expr\FuncCall(new Node\Name($modification->functionName), [new Node\Arg($node)]);
        }

        return null;
    }

    /**
     * @param array<EncapsulateMethodCallWithFunctionCall> $configuration
     * @return void
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, EncapsulateMethodCallWithFunctionCall::class);
        $this->modifications = $configuration;
    }

    private function isMatch(EncapsulateMethodCallWithFunctionCall $modification, MethodCall $methodCall): bool
    {
        $classToEncapsulate = new ObjectType($modification->className);
        if(!$this->isObjectType($methodCall->var, $classToEncapsulate)) {
            return false;
        }

        return $this->isName($methodCall->name, $modification->methodName);
    }
}
