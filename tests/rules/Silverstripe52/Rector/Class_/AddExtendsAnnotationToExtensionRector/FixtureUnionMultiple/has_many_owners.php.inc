<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureUnionMultiple;

class HasManyOwners extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureUnionMultiple;

/**
 * @extends \SilverStripe\Core\Extension<(\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne | \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo | static)>
 */
class HasManyOwners extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
