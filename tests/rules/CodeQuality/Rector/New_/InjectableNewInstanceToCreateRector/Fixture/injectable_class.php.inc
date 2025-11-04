<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Fixture;

$list = new \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Source\Foo();

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Fixture;

$list = \Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Source\Foo::create();

?>
