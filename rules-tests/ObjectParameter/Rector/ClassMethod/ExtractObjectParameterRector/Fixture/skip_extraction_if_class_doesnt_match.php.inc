<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class AnotherClass {

    public function aMethod(string $aString, AnObject $anObject)
    {
        $this->doSomething($aString, $anObject);
    }
}

?>

