<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];
    private static array $owns = ['HasOneLink'];
}

?>
