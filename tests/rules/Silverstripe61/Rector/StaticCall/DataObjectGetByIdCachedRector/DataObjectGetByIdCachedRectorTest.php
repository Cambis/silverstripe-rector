<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\AbstractSilverstripeRectorTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;

final class DataObjectGetByIdCachedRectorTest extends AbstractSilverstripeRectorTestCase
{
    #[DataProvider('provideData')]
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
