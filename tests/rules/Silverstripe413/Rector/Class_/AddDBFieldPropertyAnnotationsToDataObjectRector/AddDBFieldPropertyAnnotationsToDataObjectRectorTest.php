<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;

use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DB;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBIncomplete;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBRequiredFields;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBRequiredFieldsExtended;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture\DBRequiredFieldsExtension;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Source\RequiredFieldsExtensionMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddDBFieldPropertyAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            DB::class,
            SilverstripeConstants::PROPERTY_DB,
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

        Config::modify()->merge(
            DBComplete::class,
            SilverstripeConstants::PROPERTY_DB,
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

        Config::modify()->merge(
            DBIncomplete::class,
            SilverstripeConstants::PROPERTY_DB,
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

        Config::modify()->merge(
            DBRequiredFields::class,
            SilverstripeConstants::PROPERTY_DB,
            [
                'NotRequiredField' => 'Varchar(255)',
                'RequiredField' => 'Varchar(255)',
            ]
        );

        Config::modify()->merge(
            DBRequiredFieldsExtended::class,
            SilverstripeConstants::PROPERTY_DB,
            [
                'NotRequiredField' => 'Varchar(255)',
                'RequiredField' => 'Varchar(255)',
            ]
        );

        Config::modify()->merge(
            DBRequiredFieldsExtended::class,
            SilverstripeConstants::PROPERTY_EXTENSIONS,
            [
                RequiredFieldsExtensionMock::class,
            ]
        );

        Config::modify()->merge(
            DBRequiredFieldsExtension::class,
            SilverstripeConstants::PROPERTY_DB,
            [
                'NotRequiredField' => 'Varchar(255)',
                'RequiredField' => 'Varchar(255)',
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

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
