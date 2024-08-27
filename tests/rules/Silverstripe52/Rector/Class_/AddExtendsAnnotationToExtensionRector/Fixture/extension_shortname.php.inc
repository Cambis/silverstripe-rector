<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

use SilverStripe\Core\Extension;
use SilverStripe\Dev\TestOnly;

/**
 * @extends Extension<(\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne & static)>
 */
class ExtensionShortname extends Extension implements TestOnly
{
}
