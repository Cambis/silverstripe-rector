<?php

namespace SilverstripeRector\Tests\PHPStan\Rector\Class_\AddConfigAnnotationToConfigurablePropertiesRector\Fixture;

class ConfigurableComplete implements \SilverStripe\Dev\TestOnly
{
    use \SilverStripe\Core\Config\Configurable;

    /**
     * @config
     */
    private static array $db = [];

    /**
     * @config
     */
    private static array $has_one = [];

    /**
     * @config
     */
    private static string $configurable = 'bar';

    /**
     * @internal
     */
    private static string $not_configurable = 'baz';
}
