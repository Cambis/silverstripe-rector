<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$id = 1;
$matches = [['', 1]];

\SilverStripe\ORM\DataObject::get_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::class, 1);

\SilverStripe\ORM\DataObject::get_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::class, $id);

\SilverStripe\ORM\DataObject::get_by_id('Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo', 1);

\SilverStripe\ORM\DataObject::get_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::class, $matches[0][1]);

\SilverStripe\ORM\DataObject::get_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::class, 1, true);

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

$id = 1;
$matches = [['', 1]];

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID(1);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID($id);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID(1);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID($matches[0][1]);

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID(1);

?>
