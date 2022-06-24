<?php
declare (strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCall;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector;
use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Fixture\SomeClass;
use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\AParentClass;
use SelrahcD\RectorRules\Tests\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector\Sources\Dependency;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(
        EncapsulateMethodCallWithFunctionCallRector::class, [
        new EncapsulateMethodCallWithFunctionCall(Dependency::class, 'aDependencyMethod', 'myFunction'),
        new EncapsulateMethodCallWithFunctionCall(SomeClass::class, 'anotherMethod', 'mySecondFunction'),
        new EncapsulateMethodCallWithFunctionCall(AParentClass::class, 'methodInParent', 'myThirdFunction'),
    ]);
};
