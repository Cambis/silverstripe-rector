<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchAssign extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function doSomething(): void
    {
        self::$singular_name = 'new value';
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchAssign extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function doSomething(): void
    {
        self::config()->set('singular_name', 'new value');
    }
}

?>
