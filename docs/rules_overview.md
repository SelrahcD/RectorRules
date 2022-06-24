# 1 Rules Overview

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

-    public function aMethod() {
-        $this->anotherMethod();
-    }
+                public function aMethod() {
+                    myFunction($this->anotherMethod());
+                }

-    public function anotherMethod() {}
-}
+                public function anotherMethod() {}
+            }
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
