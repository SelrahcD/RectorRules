<?php

declare(strict_types=1);

namespace SelrahcD\RectorRules\ObjectParameter\Rector\ClassMethod;

use Rector\Core\Validation\RectorAssert;

final class ExtractObjectParameter
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
