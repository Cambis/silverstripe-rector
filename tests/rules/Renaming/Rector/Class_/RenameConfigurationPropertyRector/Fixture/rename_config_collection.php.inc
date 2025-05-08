<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use SilverStripe\Config\Collections\MemoryConfigCollection;

$collection = new MemoryConfigCollection();

$collection->get('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'description');
$collection->merge('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'description', ['']);
$collection->set('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'description', '');
$collection->remove('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'description');

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

use SilverStripe\Config\Collections\MemoryConfigCollection;

$collection = new MemoryConfigCollection();

$collection->get('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'class_description');
$collection->merge('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'class_description', ['']);
$collection->set('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'class_description', '');
$collection->remove('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Source\Foo', 'class_description');

?>
