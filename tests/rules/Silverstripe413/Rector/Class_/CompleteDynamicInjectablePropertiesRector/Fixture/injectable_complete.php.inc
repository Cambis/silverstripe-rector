<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture;

class InjectableMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    /**
     * @var \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyMock
     */
    public $dependency;
    /**
     * @var string
     */
    public $message;
    public int $integer;
    public bool $boolean;
}
