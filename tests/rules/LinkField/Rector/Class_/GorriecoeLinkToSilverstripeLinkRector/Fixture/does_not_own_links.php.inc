<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\Fixture;

class DoesNotOwnLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \gorriecoe\Link\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \gorriecoe\Link\Models\Link::class . '.Owner',
    ];

    private static array $many_many = [
        'ManyManyLinks' => \gorriecoe\Link\Models\Link::class . '.Owner',
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\Fixture;

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
