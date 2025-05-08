<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo;

Foo::config()->get('description', true);
Foo::config()->set('description', '');
Foo::config()->merge('description', ['']);
Foo::config()->uninherited('description');
Foo::config()->remove('description');

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo;

Foo::config()->get('class_description', true);
Foo::config()->set('class_description', '');
Foo::config()->merge('class_description', ['']);
Foo::config()->uninherited('class_description');
Foo::config()->remove('class_description');

?>
