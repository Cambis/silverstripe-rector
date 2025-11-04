<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Source;

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Dev\TestOnly;

class Foo implements TestOnly
{
    use Injectable;
}
