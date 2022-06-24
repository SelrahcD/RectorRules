<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod() {
        $this->anotherMethod($a, $b, $c);
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod() {
        $this->anotherMethod(new \AnotherObjectParameter($a, $b, $c));
    }
}

?>
