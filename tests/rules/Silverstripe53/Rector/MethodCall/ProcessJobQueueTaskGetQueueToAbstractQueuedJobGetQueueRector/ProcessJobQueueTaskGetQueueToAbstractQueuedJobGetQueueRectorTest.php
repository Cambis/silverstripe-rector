<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector;

use Cambis\SilverstripeRector\Testing\PHPUnit\AbstractSilverstripeRectorTestCase;
use Iterator;
use Override;

final class ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRectorTest extends AbstractSilverstripeRectorTestCase
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
