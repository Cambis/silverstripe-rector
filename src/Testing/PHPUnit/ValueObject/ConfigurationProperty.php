<?php

namespace Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject;

/**
 * @deprecated since 0.8.0
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
