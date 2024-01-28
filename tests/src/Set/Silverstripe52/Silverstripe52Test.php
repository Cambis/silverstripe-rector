<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Set\Silverstripe52;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataExtension;
use SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataExtensionFromSS413;
use SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObject;
use SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObjectComplete;
use SilverstripeRector\Tests\Set\Silverstripe52\Fixture\DataObjectFromSS413;
use SilverstripeRector\Tests\Set\Silverstripe52\Source\ExtensionMock;
use SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock;
use SilverstripeRector\Tests\Set\Silverstripe52\Source\TestConstants;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class Silverstripe52Test extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
