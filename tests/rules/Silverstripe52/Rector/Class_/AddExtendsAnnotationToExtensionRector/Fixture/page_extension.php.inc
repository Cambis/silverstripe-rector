<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

final class PageExtension extends \SilverStripe\Core\Extension
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture;

/**
 * @extends \SilverStripe\Core\Extension<(\Page & static)>
 */
final class PageExtension extends \SilverStripe\Core\Extension
{
}

?>
