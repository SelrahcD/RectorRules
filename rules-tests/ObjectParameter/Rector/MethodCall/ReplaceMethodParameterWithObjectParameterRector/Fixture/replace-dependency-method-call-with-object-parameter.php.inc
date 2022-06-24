<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Fixture;

use SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Sources\Dependency;

class SomeClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function aMethod() {
        $this->dependency->methodCall($a, $b, $c);
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Fixture;

use SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Sources\Dependency;

class SomeClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function aMethod() {
        $this->dependency->methodCall(new \ObjectParameter($a, $b, $c));
    }
}

?>
