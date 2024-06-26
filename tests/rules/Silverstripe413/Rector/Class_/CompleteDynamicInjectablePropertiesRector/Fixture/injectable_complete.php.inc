<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class InjectableComplete extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @var \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyMock
     */
    public $dependency;
    /**
     * @var string
     */
    public $message;
    public int $integer;
    public bool $boolean;
}
