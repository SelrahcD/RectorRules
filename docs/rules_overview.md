# 4 Rules Overview

## EncapsulateMethodCallWithFunctionCallRector

Encapsulate a method call with a function call.

`$this->something()` will be replaced to `aFunction($this->somethingElse())`.

This is useful as a first step before using transformation rectors such as:
 - [FuncCallToNewRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#funccalltonewrector)
 - [FuncCallToStaticCallRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#funccalltostaticcallrector)

:wrench: **configure it!**

- class: [`SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector`](../rules/Encapsulation/Rector/MethodCall/EncapsulateMethodCallWithFunctionCallRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCall;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(EncapsulateMethodCallWithFunctionCallRector::class, [new EncapsulateMethodCallWithFunctionCall('SomeClass', 'anotherMethod', 'myFunction')]);
};
```

↓

```diff
 class SomeClass {

     public function aMethod() {
-        $this->anotherMethod();
+        myFunction($this->anotherMethod());
     }

     public function anotherMethod() {}
 }
```

<br>

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCall;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(EncapsulateMethodCallWithFunctionCallRector::class, [new EncapsulateMethodCallWithFunctionCall('Dependency', 'dependencyMethod', 'myFunction')]);
};
```

↓

```diff
 class SomeClass {

     public function __construct(private Dependency $dependency) {
     }

     public function aMethod() {
-        $this->dependency->dependencyMethod();
+        myFunction($this->dependency->dependencyMethod());
     }
 }
```

<br>

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCall;
use SelrahcD\RectorRules\Encapsulation\Rector\MethodCall\EncapsulateMethodCallWithFunctionCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(EncapsulateMethodCallWithFunctionCallRector::class, [new EncapsulateMethodCallWithFunctionCall('ParentClass', 'parentMethod', 'myFunction')]);
};
```

↓

```diff
 class ParentClass {

     public method parentMethod() {
     }
 }

 class SomeClass extends ParentClass {

     public function aMethod() {
-        $this->parentMethod();
+        myFunction($this->parentMethod());
     }
 }
```

<br>

## ExtractObjectParameterRector

Extract an object parameter class for a method.

Meant to be used in conjunction with [ReplaceMethodParameterWithObjectParameterRector](#replacemethodparameterwithobjectparameterrector).

To clean up variable assignation after using this rule use [RemoveJustPropertyFetchRector](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#removejustpropertyfetchrector).

:wrench: **configure it!**

- class: [`SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector`](../rules/ObjectParameter/Rector/ClassMethod/ExtractObjectParameterRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameter;
use SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod\ExtractObjectParameterRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ExtractObjectParameterRector::class, [new ExtractObjectParameter('SomeClass', 'aMethod', 'ObjectParameter')]);
};
```

↓

```diff
 class SomeClass {

-    public function aMethod(string $aString, AnObject $anObject) {
+    public function aMethod(ObjectParameter $objectParameter) {
+        $aString = $objectParameter->aString;
+        $anObject = $objectParameter->anObject;
+    }
+}
+
+class ObjectParameter {
+    public function __construct(
+     public readonly string $aString,
+     public readonly AnObject $anObject
+    )
+    {
     }
 }
```

<br>

## MoveConstructorArgumentToMethodArgumentRector

Move constructor arguments to method arguments

:wrench: **configure it!**

- class: [`SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector`](../rules/ConstructorArgumentToMethodArgument/Rector/ClassMethod/MoveConstructorArgumentToMethodArgumentRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentParameter;
use SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod\MoveConstructorArgumentToMethodArgumentRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MoveConstructorArgumentToMethodArgumentRector::class, [new MoveConstructorArgumentToMethodArgumentParameter('SomeClass', 'execute')]);
};
```

↓

```diff
 class SomeClass {
-
-    public function __construct(
+     public function __construct(
         private string $aParameter,
         private bool $anotherParameter)
     {
     }

-    public function execute() {
-        $this->aParameter;
-        $this->anotherParameter;
+    public function execute(string $aParameter, bool $anotherParameter) {
+       $aParameter;
+       $anotherParameter;
     }
 }
```

<br>

## ReplaceMethodParameterWithObjectParameterRector

Replace method parameter with object parameter

:wrench: **configure it!**

- class: [`SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector`](../rules/ObjectParameter/Rector/MethodCall/ReplaceMethodParameterWithObjectParameterRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParametersWithObjectParameter;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceMethodParameterWithObjectParameterRector::class, [new ReplaceMethodParametersWithObjectParameter('Dependency', 'methodCall', 'ObjectParameter')]);
};
```

↓

```diff
 class SomeClass {

     public function __construct(private Dependency $dependency)
     {
     }

     public function aMethod() {
-        $this->dependency->methodCall($a, $b, $c);
+        $this->dependency->methodCall(new \ObjectParameter($a, $b, $c));
     }
 }
```

<br>

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParameterWithObjectParameterRector;
use SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParametersWithObjectParameter;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceMethodParameterWithObjectParameterRector::class, [new ReplaceMethodParametersWithObjectParameter('SomeClass', 'anotherMethod', 'ObjectParameter')]);
};
```

↓

```diff
 class SomeClass {

     public function aMethod() {
-        $this->anotherMethod($a, $b, $c);
+        $this->anotherMethod(new \ObjectParameter($a, $b, $c));
     }
 }
```

<br>
