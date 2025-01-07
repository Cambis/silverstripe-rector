<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture;

/**
 * @mixin \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\ExtensionMock
 * @mixin \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\AnotherExtensionMock
 */
class HasManyExtensions extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}
