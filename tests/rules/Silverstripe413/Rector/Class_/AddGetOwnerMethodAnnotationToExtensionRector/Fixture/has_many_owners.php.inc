<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

class HasManyOwners extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

/**
 * @method (\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockOne & static)|(\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockTwo & static) getOwner()
 */
class HasManyOwners extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
