<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;

use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyMany;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyThrough;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture\ManyManyThroughComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddManyManyMethodAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            ManyMany::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            [
                'ManyManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            ManyManyComplete::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
            [
                'ManyManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            ManyManyThrough::class,
            SilverstripeConstants::PROPERTY_MANY_MANY,
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
            SilverstripeConstants::PROPERTY_MANY_MANY,
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

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
