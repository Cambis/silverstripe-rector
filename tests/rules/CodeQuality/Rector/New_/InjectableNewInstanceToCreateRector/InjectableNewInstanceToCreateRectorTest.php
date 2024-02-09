<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;

use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InjectableNewInstanceToCreateRectorTest extends AbstractRectorTestCase
{
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
