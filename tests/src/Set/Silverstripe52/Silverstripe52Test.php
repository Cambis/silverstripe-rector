<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Set\Silverstripe52;

use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataExtension;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataExtensionFromSS413;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObject;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObjectComplete;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObjectFromSS413;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Source\ExtensionMock;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock;
use Cambis\SilverstripeRector\Tests\Set\Silverstripe52\Source\TestConstants;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class Silverstripe52Test extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            DataObject::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            DataObjectComplete::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            DataObjectFromSS413::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtension::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            RelationMock::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                DataExtension::class,
            ]
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionFromSS413::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            RelationMock::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                DataExtensionFromSS413::class,
            ]
        );
    }

    /**
     * @dataProvider provideData
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
