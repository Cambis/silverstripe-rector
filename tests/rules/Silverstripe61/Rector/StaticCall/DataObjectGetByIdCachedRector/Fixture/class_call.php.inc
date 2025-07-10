<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$id = 1;
$cached = true;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id(1);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id($id);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id($id, true);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get_by_id($id, $cached);

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$id = 1;
$cached = true;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID(1);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID($id);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID($id);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache($cached)->byID($id);

?>
