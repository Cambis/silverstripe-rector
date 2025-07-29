<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function getType(): string
    {
        return static::$singular_name;
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchStatic extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function getType(): string
    {
        return static::config()->get('singular_name');
    }
}

?>
