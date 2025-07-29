<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchInternal extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @internal
     */
    private static string $singular_name = 'Static property fetch';

    public function getType(): string
    {
        return static::$singular_name;
    }
}
