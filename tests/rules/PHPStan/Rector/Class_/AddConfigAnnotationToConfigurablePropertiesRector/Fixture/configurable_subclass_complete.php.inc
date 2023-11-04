<?php

namespace SilverstripeRector\Tests\PHPStan\Rector\Class_\AddConfigAnnotationToConfigurablePropertiesRector\Fixture;

class ConfigurableSubclass extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @var array<string, string>
     * @config
     */
    private static array $db = [];

    /**
     * @var array<class-string<\SilverStripe\ORM\DataObject>>
     * @config
     */
    private static array $has_one = [];

    /**
     * @config
     */
    private static string $configurable = 'bar';

    private string $not_configurable = 'baz';
}
