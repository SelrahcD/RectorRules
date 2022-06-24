<?php

namespace SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Fixture;

use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\AParentClass;
use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\Dependency;

class SomeClass extends AParentClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function doSomething()
    {
        $this->dependency->aDependencyMethod();
        $this->anotherMethod();
        $this->methodInParent();
        $this->aMethodNotToBeEncapsulated();
    }

    private function anotherMethod()
    {
    }

    private function aMethodNotToBeEncapsulated()
    {
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Fixture;

use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\AParentClass;
use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\Dependency;

class SomeClass extends AParentClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function doSomething()
    {
        myFunction($this->dependency->aDependencyMethod());
        mySecondFunction($this->anotherMethod());
        myThirdFunction($this->methodInParent());
        $this->aMethodNotToBeEncapsulated();
    }

    private function anotherMethod()
    {
    }

    private function aMethodNotToBeEncapsulated()
    {
    }
}

?>
