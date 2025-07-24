<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo;

Foo::config()->description;
Foo::config()->description = '';

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo;

Foo::config()->class_description;
Foo::config()->class_description = '';

?>
