<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\FixtureSkip;

class SomeClass
{
    public function aMethod(string $aString, AnObject $anObject)
    {
    }
}

?>
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\FixtureSkip;

class SomeClass
{
    public function aMethod(ObjectParameter $objectParameter)
    {
    }
}

?>
-----
ObjectParameter.php
-----
-----
<?php

namespace SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\FixtureSkip;

class ObjectParameter
{
    public function __construct(public readonly string $aString, public readonly \SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\FixtureSkip\AnObject $anObject)
    {
    }
}

