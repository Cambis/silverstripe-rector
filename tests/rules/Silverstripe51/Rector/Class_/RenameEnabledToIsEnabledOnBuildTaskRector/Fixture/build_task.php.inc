<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector\Fixture;

class BuildTask extends \SilverStripe\Dev\BuildTask
{
    protected $enabled = true;

    public function run($request)
    {
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector\Fixture;

class BuildTask extends \SilverStripe\Dev\BuildTask
{
    private static bool $is_enabled = true;

    public function run($request)
    {
    }
}

?>
