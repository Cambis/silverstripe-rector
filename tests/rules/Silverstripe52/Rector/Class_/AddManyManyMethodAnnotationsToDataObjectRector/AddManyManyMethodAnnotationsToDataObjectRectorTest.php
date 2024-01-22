<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyMany;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyComplete;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyThrough;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyThroughComplete;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class AddManyManyMethodAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            ManyMany::class,
            SilverstripeConstants::MANY_MANY,
            [
                'ManyManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            ManyManyComplete::class,
            SilverstripeConstants::MANY_MANY,
            [
                'ManyManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            ManyManyThrough::class,
            SilverstripeConstants::MANY_MANY,
            [
                'ManyManyThroughRelationship' => [
                    'through' => RelationMock::class,
                    'from' => '',
                    'to' => '',
                ],
            ]
        );

        Config::modify()->merge(
            ManyManyThroughComplete::class,
            SilverstripeConstants::MANY_MANY,
            [
                'ManyManyThroughRelationship' => [
                    'through' => RelationMock::class,
                    'from' => '',
                    'to' => '',
                ],
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