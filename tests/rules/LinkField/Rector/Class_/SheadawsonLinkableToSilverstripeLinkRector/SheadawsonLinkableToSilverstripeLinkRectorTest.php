<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\AbstractSilverstripeRectorTestCase;
use Iterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;

final class SheadawsonLinkableToSilverstripeLinkRectorTest extends AbstractSilverstripeRectorTestCase
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

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
