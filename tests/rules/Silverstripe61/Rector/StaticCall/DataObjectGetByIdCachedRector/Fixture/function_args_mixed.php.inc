<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

use SilverStripe\ORM\DataObject;

final class Foo extends DataObject
{
    public static function getById($id, $cached = false): ?self
    {
        return DataObject::get_by_id(self::class, $id, $cached);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture;

use SilverStripe\ORM\DataObject;

final class Foo extends DataObject
{
    public static function getById($id, $cached = false): ?self
    {
        return \Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector\Fixture\Foo::get()->setUseCache($cached)->byID($id);
    }
}

?>
