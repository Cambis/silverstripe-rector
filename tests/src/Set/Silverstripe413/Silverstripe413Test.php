<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Set\Silverstripe413;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Set\Silverstripe413\Fixture\DataExtensionMock;
use SilverstripeRector\Tests\Set\Silverstripe413\Fixture\DataObjectMock;
use SilverstripeRector\Tests\Set\Silverstripe413\Source\ExtensionMock;
use SilverstripeRector\Tests\Set\Silverstripe413\Source\RelationMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class Silverstripe413Test extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            DataObjectMock::class,
            SilverstripeConstants::EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::BELONGS_MANY_MANY,
            TestConstants::BELONGS_MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::BELONGS_TO,
            TestConstants::BELONGS_TO_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::DB,
            TestConstants::DB_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::HAS_ONE,
            TestConstants::HAS_ONE_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::HAS_MANY,
            TestConstants::HAS_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::MANY_MANY,
            TestConstants::MANY_MANY_CONFIG,
        );

        Config::modify()->merge(
            DataExtensionMock::class,
            SilverstripeConstants::MANY_MANY,
            TestConstants::MANY_MANY_THROUGH_CONFIG,
        );

        Config::modify()->merge(
            RelationMock::class,
            SilverstripeConstants::EXTENSIONS,
            [
                DataExtensionMock::class,
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
