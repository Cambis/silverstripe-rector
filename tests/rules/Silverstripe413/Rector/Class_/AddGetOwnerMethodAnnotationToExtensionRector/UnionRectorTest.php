<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector;

use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnionRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     * @runInSeparateProcess
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureUnion');
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/union.php';
    }
}
