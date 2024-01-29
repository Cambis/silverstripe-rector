<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class IntersectionRectorTest extends AbstractRectorTestCase
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
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureIntersection');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/intersection.php';
    }
}
