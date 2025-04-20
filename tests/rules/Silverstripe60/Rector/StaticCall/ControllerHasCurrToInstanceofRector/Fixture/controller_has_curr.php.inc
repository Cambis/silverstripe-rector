<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector\Fixture;

class ControllerHasCurr
{
    public function doSomething(): void
    {
        if (\SilverStripe\Control\Controller::has_curr()) {
            echo 'has current.';
            return;
        }

        echo 'does not have current';
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector\Fixture;

class ControllerHasCurr
{
    public function doSomething(): void
    {
        if (\SilverStripe\Control\Controller::curr() instanceof \SilverStripe\Control\Controller) {
            echo 'has current.';
            return;
        }

        echo 'does not have current';
    }
}

?>
