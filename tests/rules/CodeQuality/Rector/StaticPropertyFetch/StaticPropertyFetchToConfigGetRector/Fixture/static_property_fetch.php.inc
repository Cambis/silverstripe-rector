<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector\Fixture;

class StaticPropertyFetch extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    /**
     * @internal
     */
    private static string $internal_only = 'Internal only';

    public function getType(): string
    {
        return self::$singular_name;
    }

    public function getInternalOnly(): string
    {
        return self::$internal_only;
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector\Fixture;

class StaticPropertyFetch extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $singular_name = 'Static property fetch';

    /**
     * @internal
     */
    private static string $internal_only = 'Internal only';

    public function getType(): string
    {
        return $this->config()->get('singular_name');
    }

    public function getInternalOnly(): string
    {
        return self::$internal_only;
    }
}

?>
