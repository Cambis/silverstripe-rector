<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class AddDBFieldPropertyAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            DBMock::class,
            SilverstripeConstants::DB,
            [
                'Boolean' => 'Boolean',
                'Currency' => 'Currency',
                'Date' => 'Date',
                'Decimal' => 'Decimal',
                'Enum' => 'Enum',
                'HTMLText' => 'HTMLText',
                'HTMLVarchar' => 'HTMLVarchar',
                'Int' => 'Int',
                'Percentage' => 'Percentage',
                'Datetime' => 'Datetime',
                'Text' => 'Text',
                'Time' => 'Time',
                'Varchar' => 'Varchar(255)',
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
