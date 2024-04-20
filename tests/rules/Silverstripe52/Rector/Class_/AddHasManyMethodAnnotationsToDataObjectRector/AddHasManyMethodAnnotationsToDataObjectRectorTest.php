<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;

use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture\HasMany;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture\HasManyComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture\HasManyFromSS4;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture\HasManyShortname;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;

final class AddHasManyMethodAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            HasMany::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            [
                'HasManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            HasManyComplete::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            [
                'HasManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            HasManyShortname::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            [
                'HasManyRelationship' => RelationMock::class,
            ]
        );

        Config::modify()->merge(
            HasManyFromSS4::class,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            [
                'HasManyRelationship' => RelationMock::class,
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
