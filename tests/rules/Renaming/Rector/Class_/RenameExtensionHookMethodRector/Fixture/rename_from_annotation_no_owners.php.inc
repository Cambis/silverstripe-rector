<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<static>
 */
final class RenameFromAnnotationNoOwners extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateDoSomething(): void
    {
    }
}
