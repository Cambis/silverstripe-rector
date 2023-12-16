<?php
namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Fixture;

/**
 * @method string someMethod()
 */
class HasOneOwnerMock extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}
