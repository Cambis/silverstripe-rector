<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class DoesNotOwnLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \Sheadawson\Linkable\Models\Link::class . '.Owner',
    ];

    private static array $many_many = [
        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class . '.Owner',
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class DoesNotOwnLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner', 'ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];
}

?>
