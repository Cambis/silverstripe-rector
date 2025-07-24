<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

use Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Source\Foo;

Foo::config()->bar;

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Fixture;

use Cambis\SilverstripeRector\Tests\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector\Source\Foo;

Foo::config()->get('bar');

?>
