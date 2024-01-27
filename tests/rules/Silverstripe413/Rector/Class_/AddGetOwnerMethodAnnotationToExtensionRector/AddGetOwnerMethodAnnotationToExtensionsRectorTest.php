<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture\HasOneOwner;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Fixture\HasOneOwnerComplete;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector\Source\OwnerMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class AddGetOwnerMethodAnnotationToExtensionRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            OwnerMock::class,
            SilverstripeConstants::EXTENSIONS,
            [
                HasOneOwner::class,
            ]
        );

        Config::modify()->merge(
            OwnerMock::class,
            SilverstripeConstants::EXTENSIONS,
            [
                HasOneOwnerComplete::class,
            ]
        );
    }

    /**
     * @dataProvider provideData
     */
    public function test(string $filePath): void
    {
        $this->markTestIncomplete('TODO');
        // $this->doTestFile($filePath);
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
