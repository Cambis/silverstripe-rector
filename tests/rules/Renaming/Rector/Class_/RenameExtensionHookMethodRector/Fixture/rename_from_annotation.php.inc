<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<\Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Source\Foo>
 */
final class RenameFromAnnotation extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateDoSomething(): void
    {
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<\Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Source\Foo>
 */
final class RenameFromAnnotation extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateDoSomethingElse(): void
    {
    }
}

?>