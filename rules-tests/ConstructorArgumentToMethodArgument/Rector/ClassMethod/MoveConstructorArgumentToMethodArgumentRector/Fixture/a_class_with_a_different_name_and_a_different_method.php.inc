<?php

namespace SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture;

class AClassWithADifferentNameAndADifferentMethod {

    public function __construct(
        private string $aParameter,
        private bool $anotherParameter)
    {
    }

    public function aDifferentMethod() {
        $this->aParameter = null;
        $b = $this->anotherParameter;

        $this->aParameter + $this->anotherParameter;
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture;

class AClassWithADifferentNameAndADifferentMethod {

    public function __construct()
    {
    }

    public function aDifferentMethod(string $aParameter, bool $anotherParameter) {
        $aParameter = null;
        $b = $anotherParameter;

        $aParameter + $anotherParameter;
    }
}

?>
