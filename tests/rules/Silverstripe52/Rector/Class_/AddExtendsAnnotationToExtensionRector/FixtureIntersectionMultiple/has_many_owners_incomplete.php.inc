<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureIntersectionMultiple;

/**
 * @extends \SilverStripe\Core\Extension<(\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static)>
 */
class HasManyOwnersIncomplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureIntersectionMultiple;

/**
 * @extends \SilverStripe\Core\Extension<((\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static) | (\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo & static))>
 */
class HasManyOwnersIncomplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}

?>
