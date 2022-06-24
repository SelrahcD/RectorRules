<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParametersWithObjectParameter;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector;
use SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Fixture\SomeClass;
use SelrahcD\RectorRules\Tests\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector\Sources\Dependency;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(ReplaceMethodParameterWithObjectParameterRector::class, [
        new ReplaceMethodParametersWithObjectParameter(Dependency::class, 'methodCall', 'ObjectParameter'),
        new ReplaceMethodParametersWithObjectParameter(SomeClass::class, 'anotherMethod', 'AnotherObjectParameter'),
    ]);
};
