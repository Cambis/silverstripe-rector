<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\ValueObject\ConfigurationProperty;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureUnion\HasManyOwners;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureUnion\HasManyOwnersComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\FixtureUnion\HasManyOwnersInComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class UnionRectorTest extends AbstractRectorTestCase
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
        yield [
            [
                new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwners::class]),
                new ConfigurationProperty(OwnerMockTwo::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwners::class]),
            ],
            __DIR__ . '/FixtureUnion/has_many_owners.php.inc',
        ];

        yield [
            [
                new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwnersComplete::class]),
                new ConfigurationProperty(OwnerMockTwo::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwnersComplete::class]),
            ],
            __DIR__ . '/FixtureUnion/has_many_owners_complete.php.inc',
        ];

        yield [
            [
                new ConfigurationProperty(OwnerMockOne::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwnersInComplete::class]),
                new ConfigurationProperty(OwnerMockTwo::class, SilverstripeConstants::PROPERTY_EXTENSIONS, [HasManyOwnersInComplete::class]),
            ],
            __DIR__ . '/FixtureUnion/has_many_owners_incomplete.php.inc',
        ];
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/union.php';
    }
}
