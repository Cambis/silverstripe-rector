<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class InjectableInterface extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class InjectableInterface extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @var \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyInterface
     */
    public $dependency;
}

?>