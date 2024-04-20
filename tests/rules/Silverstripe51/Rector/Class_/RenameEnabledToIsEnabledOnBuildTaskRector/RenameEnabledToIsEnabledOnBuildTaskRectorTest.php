<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector;

use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameEnabledToIsEnabledOnBuildTaskRectorTest extends AbstractRectorTestCase
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
