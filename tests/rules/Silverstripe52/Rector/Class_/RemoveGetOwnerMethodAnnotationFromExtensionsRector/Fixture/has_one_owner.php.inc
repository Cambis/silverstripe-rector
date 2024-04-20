<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Fixture;

/**
 * @method \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Source\OwnerMock|static getOwner()
 */
class HasOneOwner extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Fixture;

class HasOneOwner extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
