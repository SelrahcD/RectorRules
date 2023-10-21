<?php

declare (strict_types=1);
namespace RectorPrefix202206;

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture\AClassWithADifferentName;
use SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture\AClassWithADifferentNameAndADifferentMethod;
use SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\Fixture\SomeClass;
use SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentParameter;
use SelrahcD\RectorRules\Tests\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector\FixtureSkip\AClassWithoutAnExecuteMethod;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(\SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector::class,
    [
        new MoveConstructorArgumentToMethodArgumentParameter(SomeClass::class, 'execute'),
        new MoveConstructorArgumentToMethodArgumentParameter(AClassWithADifferentName::class, 'execute'),
        new MoveConstructorArgumentToMethodArgumentParameter(AClassWithADifferentNameAndADifferentMethod::class, 'aDifferentMethod'),
        new MoveConstructorArgumentToMethodArgumentParameter(AClassWithoutAnExecuteMethod::class, 'execute'),
    ]
    );
};
