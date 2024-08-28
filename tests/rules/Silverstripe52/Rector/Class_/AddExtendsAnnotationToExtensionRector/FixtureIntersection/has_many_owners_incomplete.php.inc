<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureIntersection;

/**
 * @extends \SilverStripe\Core\Extension<(\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static)>
 */
class HasManyOwnersIncomplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureIntersection;

/**
 * @extends \SilverStripe\Core\Extension<((\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static) | (\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo & static))>
 */
class HasManyOwnersIncomplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
