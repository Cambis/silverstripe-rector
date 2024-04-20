<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

class HasOneOwner extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
------
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

/**
 * @method \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMock&static getOwner()
 */
class HasOneOwner extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
