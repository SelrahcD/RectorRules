<?php

declare(strict_types=1);

use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

return new RuleDefinition(
    'Replace method parameter with object parameter',
    [
        new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
class SomeClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function aMethod() {
        $this->dependency->methodCall($a, $b, $c);
    }
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
class SomeClass {

    public function __construct(private Dependency $dependency)
    {
    }

    public function aMethod() {
        $this->dependency->methodCall(new \ObjectParameter($a, $b, $c));
    }
}
CODE_SAMPLE,
            [new \SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParametersWithObjectParameter('Dependency', 'methodCall', 'ObjectParameter')]
        ),

        new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod() {
        $this->anotherMethod($a, $b, $c);
    }
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
class SomeClass {

    public function aMethod() {
        $this->anotherMethod(new \ObjectParameter($a, $b, $c));
    }
}
CODE_SAMPLE,
            [new \SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall\ReplaceMethodParametersWithObjectParameter('SomeClass', 'anotherMethod', 'ObjectParameter')]
        )

    ]

);

