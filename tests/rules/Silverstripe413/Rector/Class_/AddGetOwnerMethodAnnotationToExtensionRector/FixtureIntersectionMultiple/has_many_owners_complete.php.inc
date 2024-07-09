<?php
namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\FixtureIntersectionMultiple;

/**
 * @method ((\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockOne & static)|(\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockTwo & static)) getOwner()
 */
class HasManyOwnersComplete extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
}
