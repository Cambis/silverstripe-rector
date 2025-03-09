<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \gorriecoe\Link\Models\Link::class,
    ];
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector\Fixture;

class HasOneLink extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];
    private static array $owns = ['HasOneLink'];
}

?>
