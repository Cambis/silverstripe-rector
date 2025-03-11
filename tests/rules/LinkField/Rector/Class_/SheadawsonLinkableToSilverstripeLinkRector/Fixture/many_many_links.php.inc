<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $many_many = [
        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class,
    ];

    private static array $many_many_extraFields = [
        'ManyManyLinks' => [
            'Sort' => 'Int',
        ],
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector\Fixture;

class ManyManyLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_many = ['ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner'];

    private static array $owns = ['ManyManyLinks'];
}

?>
