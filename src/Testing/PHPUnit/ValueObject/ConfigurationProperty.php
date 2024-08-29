<?php

namespace Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject;

final class ConfigurationProperty
{
    /**
     * @readonly
     */
    public string $className;
    /**
     * @readonly
     */
    public string $propertyName;
    /**
     * @readonly
     * @var mixed
     */
    public $value;
    /**
     * @param mixed $value
     */
    public function __construct(string $className, string $propertyName, $value)
    {
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->value = $value;
    }
}
