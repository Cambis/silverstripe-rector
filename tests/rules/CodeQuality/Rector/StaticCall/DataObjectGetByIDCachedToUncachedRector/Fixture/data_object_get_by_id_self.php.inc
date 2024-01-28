<?php

namespace SilverstripeRector\Tests\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector\Fixture;

$foo = \SilverStripe\ORM\DataObject::get_by_id(\SilverStripe\Assets\File::class, 1);
