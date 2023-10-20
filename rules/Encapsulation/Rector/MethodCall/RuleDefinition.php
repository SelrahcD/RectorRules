<?php

declare(strict_types=1);

use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

return new RuleDefinition(<<<'DESCRIPTION'
Encapsulate a method call with a function call.

`$this->something()` will be replaced to `aFunction($this->somethingElse())`.

This is useful as a first step before using transformation rectors such as:
 - [FuncCallToNewRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#funccalltonewrector)
 - [FuncCallToStaticCallRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#funccalltostaticcallrector)

DESCRIPTION
, [
    new ConfiguredCodeSample(
        <<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod() {
        $this->anotherMethod();
    }

    public function anotherMethod() {}
}
CODE_SAMPLE
        , <<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod() {
        myFunction($this->anotherMethod());
    }

    public function anotherMethod() {}
}
CODE_SAMPLE
        , [new EncapsulateMethodCallWithFunctionCall('SomeClass', 'anotherMethod', 'myFunction')]
    ),


    new ConfiguredCodeSample(
        <<<'CODE_SAMPLE'
class SomeClass {

    public function __construct(private Dependency $dependency) {
    }

    public function aMethod() {
        $this->dependency->dependencyMethod();
    }
}
CODE_SAMPLE
        , <<<'CODE_SAMPLE'
class SomeClass {

    public function __construct(private Dependency $dependency) {
    }

    public function aMethod() {
        myFunction($this->dependency->dependencyMethod());
    }
}
CODE_SAMPLE
        , [new EncapsulateMethodCallWithFunctionCall('Dependency', 'dependencyMethod', 'myFunction')]
    ),


    new ConfiguredCodeSample(
        <<<'CODE_SAMPLE'

class ParentClass {

    public method parentMethod() {
    }
}

class SomeClass extends ParentClass {

    public function aMethod() {
        $this->parentMethod();
    }
}
CODE_SAMPLE
        , <<<'CODE_SAMPLE'
class ParentClass {

    public method parentMethod() {
    }
}

class SomeClass extends ParentClass {

    public function aMethod() {
        myFunction($this->parentMethod());
    }
}
CODE_SAMPLE
        , [new EncapsulateMethodCallWithFunctionCall('ParentClass', 'parentMethod', 'myFunction')]
    )

]);
