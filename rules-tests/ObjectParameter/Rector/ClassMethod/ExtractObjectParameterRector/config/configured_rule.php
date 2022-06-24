<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameter;
use SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector;
use SelrahcD\RectorRules\Tests\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector\Fixture\SomeClass;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(ExtractObjectParameterRector::class, [
        new ExtractObjectParameter(SomeClass::class, 'aMethod', 'ObjectParameter')
    ]);
};
