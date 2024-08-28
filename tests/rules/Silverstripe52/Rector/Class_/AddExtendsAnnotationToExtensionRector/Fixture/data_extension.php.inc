<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

class DataExtension extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

/**
 * @extends \SilverStripe\ORM\DataExtension<(\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static)>
 */
class DataExtension extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
