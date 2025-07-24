<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchClass extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function getType(): string
    {
        return StaticPropertyFetchClass::$singular_name;
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

class StaticPropertyFetchClass extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    public function getType(): string
    {
        return \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture\StaticPropertyFetchClass::config()->get('singular_name');
    }
}

?>
