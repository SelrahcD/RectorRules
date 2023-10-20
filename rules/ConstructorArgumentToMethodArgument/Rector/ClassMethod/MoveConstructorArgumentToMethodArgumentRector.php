<?php

declare (strict_types=1);
namespace SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**

* @see \Rector\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\MoveConstructorArgumentToMethodArgumentRectorTest
*/
final class MoveConstructorArgumentToMethodArgumentRector extends AbstractRector
{

    /**
     * @var array|Node\Expr[]|string[]
     */
    private array $constructMethodParamNames = [];

    public function __construct(private readonly BetterNodeFinder $nodeFinder)
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Move constructor arguments to method arguments', [new CodeSample(<<<'CODE_SAMPLE'
class SomeClass {

    public function __construct(
        private string $aParameter,
        private bool $anotherParameter)
    {
    }

    public function execute() {
        $this->aParameter;
        $this->anotherParameter;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass {
     public function __construct(
        private string $aParameter,
        private bool $anotherParameter)
    {
    }

    public function execute(string $aParameter, bool $anotherParameter) {
       $aParameter;
       $anotherParameter;
    }
}
CODE_SAMPLE
)]);
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
        if($node instanceof Class_) {
            return $this->refactorClass($node);
        }
        elseif ($node instanceof Node\Expr\PropertyFetch) {
            return $this->refactorPropertyFetch($node);
        }

        return null;
    }

    private function refactorClass(Class_ $class): ?Node
    {
        $constructMethod = $this->nodeFinder->findFirst($class, fn (Node $node) => $node instanceof ClassMethod && $this->isName($node->name, '__construct'));

        if(!$constructMethod instanceof ClassMethod) {
            return null;
        }

        $this->constructMethodParamNames = array_map(fn(Node\Param $param) => $param->var->name , $constructMethod->params);


        $executeMethod = $this->nodeFinder->findFirst($class, fn (Node $node) => $node instanceof ClassMethod && $this->isName($node->name, 'execute'));

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

    private function refactorPropertyFetch(Node\Expr\PropertyFetch $node)
    {
        if(!$this->isNames($node, $this->constructMethodParamNames)) {
            return null;
        }


        return new Node\Expr\Variable($node->name);
    }
}
