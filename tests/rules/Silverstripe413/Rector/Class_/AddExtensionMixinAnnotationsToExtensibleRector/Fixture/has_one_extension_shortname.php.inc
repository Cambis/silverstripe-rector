<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\ExtensionMock;

/**
 * @mixin ExtensionMock
 */
class HasOneExtensionShortname extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}
