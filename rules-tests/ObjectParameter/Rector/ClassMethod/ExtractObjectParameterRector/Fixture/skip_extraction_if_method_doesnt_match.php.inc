<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class SomeClass {

    public function anotherMethod(string $aString, AnObject $anObject)
    {
        $this->doSomething($aString, $anObject);
    }
}

?>

