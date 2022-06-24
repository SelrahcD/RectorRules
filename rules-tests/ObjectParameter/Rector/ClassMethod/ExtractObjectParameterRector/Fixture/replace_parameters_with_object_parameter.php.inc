<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod(string $aString, AnObject $anObject)
    {
        $this->doSomething($aString, $anObject);
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod(ObjectParameter $objectParameter)
    {
        $aString = $objectParameter->aString;
        $anObject = $objectParameter->anObject;
        $this->doSomething($aString, $anObject);
    }
}
class ObjectParameter
{
    public function __construct(public readonly string $aString, public readonly AnObject $anObject)
    {
    }
}

?>
