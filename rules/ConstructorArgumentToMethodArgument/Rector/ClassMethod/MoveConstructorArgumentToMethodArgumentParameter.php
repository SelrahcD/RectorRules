<?php

declare(strict_types=1);

namespace SelrahcD\RectorRules\ConstructorArgumentToMethodArgument\Rector\ClassMethod;

use Rector\Core\Validation\RectorAssert;

final class MoveConstructorArgumentToMethodArgumentParameter
{
    public function __construct(
        public readonly string $className,
        public readonly string $methodName,
    )
    {
        RectorAssert::className($this->className);
    }
}
