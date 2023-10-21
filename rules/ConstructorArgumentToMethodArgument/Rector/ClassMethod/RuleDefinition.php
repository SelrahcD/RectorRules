<?php

declare(strict_types=1);

use SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentParameter;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

return new RuleDefinition('Move constructor arguments to method arguments', [
    new ConfiguredCodeSample(<<<'CODE_SAMPLE'
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
CODE_SAMPLE,
        [
            new MoveConstructorArgumentToMethodArgumentParameter('SomeClass', 'execute')
        ]
    )]);
