<?php

namespace SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture;

class AClassWithADifferentName {

    public function __construct(
        private string $aParameter,
        private bool $anotherParameter)
    {
    }

    public function execute() {
        $this->aParameter = null;
        $b = $this->anotherParameter;

        $this->aParameter + $this->anotherParameter;
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture;

class AClassWithADifferentName {

    public function __construct()
    {
    }

    public function execute(string $aParameter, bool $anotherParameter) {
        $aParameter = null;
        $b = $anotherParameter;

        $aParameter + $anotherParameter;
    }
}

?>
