<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector\Fixture;

class RemoteFileModalExtensionCalls extends \SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension
{
    public function doSomething(): void
    {
        $this->getRequest();
        $this->getSchemaResponse('schema');
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector\Fixture;

class RemoteFileModalExtensionCalls extends \SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension
{
    public function doSomething(): void
    {
        $this->getOwner()->getRequest();
        $this->getOwner()->getSchemaResponse('schema');
    }
}

?>
