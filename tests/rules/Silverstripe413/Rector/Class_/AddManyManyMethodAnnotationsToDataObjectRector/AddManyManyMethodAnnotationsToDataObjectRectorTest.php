<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\AbstractSilverstripeRectorTestCase;
use Iterator;
use Override;

final class AddManyManyMethodAnnotationsToDataObjectRectorTest extends AbstractSilverstripeRectorTestCase
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
