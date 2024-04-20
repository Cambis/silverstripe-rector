<?php
namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureIntersectionMultiple;

/**
 * @extends \SilverStripe\Core\Extension<((\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static) | (\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo & static))>
 */
class HasManyOwnersComplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}
