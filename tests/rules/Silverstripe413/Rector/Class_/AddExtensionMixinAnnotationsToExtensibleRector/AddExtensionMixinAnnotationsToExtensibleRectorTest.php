<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;

use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture\HasOneExtension;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture\HasOneExtensionComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture\HasOneExtensionShortname;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Fixture\HasOneExtensionSuffixed;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\Source\ExtensionMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddExtensionMixinAnnotationsToExtensibleRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            HasOneExtension::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            HasOneExtensionComplete::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            HasOneExtensionShortname::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            HasOneExtensionSuffixed::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                ExtensionMock::class . "('Foo', 'Bar')",
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
