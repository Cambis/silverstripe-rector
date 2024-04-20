<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class Injectable extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class Injectable extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @var \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyMock
     */
    public $dependency;
    /**
     * @var string
     */
    public $message;
    /**
     * @var int
     */
    public $integer;
    /**
     * @var bool
     */
    public $boolean;
}

?>
