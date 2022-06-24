<?php

declare(strict_types=1);

namespace SelrahcD\RectorRules\ObjectParameter\Rector\MethodCall;

use Rector\Core\Validation\RectorAssert;

final class ReplaceMethodParametersWithObjectParameter
{
    public function __construct(
        public readonly string $className,
        public readonly string $methodName,
        public readonly string $objectParameterClassName,
    )
    {
        RectorAssert::className($this->className);
    }
}
