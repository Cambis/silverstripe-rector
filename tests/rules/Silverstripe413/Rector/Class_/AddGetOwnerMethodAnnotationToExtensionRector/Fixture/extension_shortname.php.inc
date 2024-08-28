<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture;

use SilverStripe\Core\Extension;
use SilverStripe\Dev\TestOnly;

/**
 * @method (\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMockOne & static) getOwner()
 */
class ExtensionShortname extends Extension implements TestOnly
{
}
