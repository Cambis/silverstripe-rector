<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject\ConfigurationProperty;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture\DataExtension;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture\Extension;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture\ExtensionComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Fixture\ExtensionShortname;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddExtendsAnnotationToExtensionRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     * @param ConfigurationProperty[] $configurationProperties
     */
    public function test(array $configurationProperties, string $filePath): void
    {
        foreach ($configurationProperties as $configurationProperty) {
            Config::modify()->set(
                $configurationProperty->className,
                $configurationProperty->propertyName,
                $configurationProperty->value,
            );
        }

        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        yield [[new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [DataExtension::class])], __DIR__ . '/Fixture/data_extension.php.inc'];
        yield [[new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [Extension::class])], __DIR__ . '/Fixture/extension.php.inc'];
        yield [[new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [ExtensionComplete::class])], __DIR__ . '/Fixture/extension_complete.php.inc'];
        yield [[new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [ExtensionShortname::class])], __DIR__ . '/Fixture/extension_shortname.php.inc'];
        yield [[new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [ExtensionShortname::class])], __DIR__ . '/Fixture/extension_shortname.php.inc'];
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
