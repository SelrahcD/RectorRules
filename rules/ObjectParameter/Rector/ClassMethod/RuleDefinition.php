<?php

declare(strict_types=1);


use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

return new RuleDefinition(<<<'DESCRIPTION'
Extract an object parameter class for a method.

Meant to be used in conjunction with [ReplaceMethodParameterWithObjectParameterRector](#replacemethodparameterwithobjectparameterrector).

To clean up variable assignation after using this rule use [RemoveJustPropertyFetchRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#removejustpropertyfetchrector).
DESCRIPTION
, [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod(string $aString, AnObject $anObject) {
    }
}
CODE_SAMPLE
    , <<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod(ObjectParameter $objectParameter) {
        $aString = $objectParameter->aString;
        $anObject = $objectParameter->anObject;
    }
}

class ObjectParameter {
    public function __construct(
     public readonly string $aString,
     public readonly AnObject $anObject
    )
    {
    }
}
CODE_SAMPLE,
    [new \SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameter('SomeClass', 'aMethod', 'ObjectParameter')]
)]);
