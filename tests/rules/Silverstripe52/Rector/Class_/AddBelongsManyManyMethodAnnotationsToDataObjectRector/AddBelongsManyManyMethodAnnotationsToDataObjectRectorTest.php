<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;

use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture\BelongsManyMany;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture\BelongsManyManyComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddBelongsManyManyMethodAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            BelongsManyMany::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            [
                'BelongsManyManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            BelongsManyManyComplete::class,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            [
                'BelongsManyManyRelationship' => RelationMock::class,
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
