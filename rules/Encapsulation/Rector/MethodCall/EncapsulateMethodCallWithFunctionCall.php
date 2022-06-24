<?php

declare(strict_types=1);

namespace SelrahcD\RectorRules\Encapsulation\Rector\MethodCall;

final class EncapsulateMethodCallWithFunctionCall
{
    public function __construct(
        public readonly string $className,
        public readonly string $methodName,
        public readonly string $functionName)
    {
    }
}