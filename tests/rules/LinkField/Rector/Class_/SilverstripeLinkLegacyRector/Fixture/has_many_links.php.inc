<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SilverstripeLinkLegacyRector\Fixture;

class HasManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class,
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SilverstripeLinkLegacyRector\Fixture;

class HasManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];
    private static array $owns = ['HasManyLinks'];
}

?>
