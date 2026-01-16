<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

\SilverStripe\ORM\DataObject::get_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::class, 'bad');

\SilverStripe\ORM\DataObject::get_by_id('Not a class', 1);
