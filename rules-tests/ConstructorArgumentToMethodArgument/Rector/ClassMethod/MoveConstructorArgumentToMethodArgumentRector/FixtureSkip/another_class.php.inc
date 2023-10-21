<?php

namespace SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\FixtureSkip;

class SomeClass {

    public function __construct(
        private string $aParameter,
        private bool $anotherParameter)
    {
    }

    public function execute() {
    }
}
?>
