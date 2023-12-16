<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionsRector\Fixture;

class HasOneOwnerMock extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
------
<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionsRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionsRector\Source\OwnerMock|static>
 */
class HasOneOwnerMock extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
