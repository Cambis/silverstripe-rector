<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;

use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnionMultipleRectorTest extends AbstractRectorTestCase
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
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureUnionMultiple');
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/union_multiple.php';
    }
}
