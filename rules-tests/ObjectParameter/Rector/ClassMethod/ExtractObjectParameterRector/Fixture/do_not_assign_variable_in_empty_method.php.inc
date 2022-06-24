<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod(string $aString, AnObject $anObject)
    {
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture;

class SomeClass {

    public function aMethod(ObjectParameter $objectParameter)
    {
    }
}
class ObjectParameter
{
    public function __construct(public readonly string $aString, public readonly AnObject $anObject)
    {
    }
}

?>
