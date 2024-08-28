<?php

namespace Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject;

final readonly class ConfigurationProperty
{
    public function __construct(
        public string $className,
        public string $propertyName,
        public mixed $value,
    ) {
    }
}
