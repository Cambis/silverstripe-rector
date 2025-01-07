<?php

namespace Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject;

/**
 * @deprecated since 1.0.0
 */
final readonly class ConfigurationProperty
{
    public function __construct(
        public string $className,
        public string $propertyName,
        public mixed $value,
    ) {
    }
}
