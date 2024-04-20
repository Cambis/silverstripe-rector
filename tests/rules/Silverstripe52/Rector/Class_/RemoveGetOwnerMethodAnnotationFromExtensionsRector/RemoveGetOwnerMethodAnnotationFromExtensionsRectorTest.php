<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector;

use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Fixture\HasOneOwner;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Fixture\HasOneOwnerComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector\Source\OwnerMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class RemoveGetOwnerMethodAnnotationFromExtensionsRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            OwnerMock::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                HasOneOwner::class,
            ]
        );

        Config::modify()->merge(
            OwnerMock::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
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
