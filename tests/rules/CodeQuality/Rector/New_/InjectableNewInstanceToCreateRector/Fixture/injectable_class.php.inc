<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Fixture;

$list = new \SilverStripe\ORM\ArrayList();

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector\Fixture;

$list = \SilverStripe\ORM\ArrayList::create();

?>
