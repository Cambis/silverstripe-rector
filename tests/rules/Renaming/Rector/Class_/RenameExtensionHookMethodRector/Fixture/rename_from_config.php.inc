<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture;

final class RenameFromConfig extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateDoSomething(): void
    {
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture;

final class RenameFromConfig extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    protected function updateDoSomethingElse(): void
    {
    }
}

?>