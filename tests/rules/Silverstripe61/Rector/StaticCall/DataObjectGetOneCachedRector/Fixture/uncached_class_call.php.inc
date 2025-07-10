<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

$cached = false;

\SilverStripe\ORM\DataObject::get_one(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::class, '', false);

\SilverStripe\ORM\DataObject::get_one(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::class, '', $cached);

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Fixture;

$cached = false;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->first();

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector\Source\Foo::get()->setUseCache($cached)->first();

?>
