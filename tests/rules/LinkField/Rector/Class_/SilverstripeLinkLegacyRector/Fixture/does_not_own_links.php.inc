<?php

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SilverstripeLinkLegacyRector\Fixture;

class DoesNotOwnLinks extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static array $has_one = [
        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
    ];

    private static array $has_many = [
        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
    ];
}
