<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Fixture;

$id = 1;

\SilverStripe\ORM\DataObject::delete_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Source\Foo::class, 1);

\SilverStripe\ORM\DataObject::delete_by_id(\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Source\Foo::class, $id);

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Fixture;

$id = 1;

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID(1)?->delete();

\Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector\Source\Foo::get()->setUseCache(true)->byID($id)?->delete();

?>
