<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

$cached = true;

\SilverStripe\ORM\DataObject::get_one(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::class);

\SilverStripe\ORM\DataObject::get_one(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::class, '', true);

\SilverStripe\ORM\DataObject::get_one(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::class, '', $cached);

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

$cached = true;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->setUseCache(true)->first();

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->setUseCache(true)->first();

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->setUseCache($cached)->first();

?>
