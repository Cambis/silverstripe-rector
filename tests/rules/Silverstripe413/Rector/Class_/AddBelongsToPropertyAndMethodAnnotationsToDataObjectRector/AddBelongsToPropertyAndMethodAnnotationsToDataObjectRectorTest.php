<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Fixture\BelongsToMock;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class AddBelongsToPropertyAndMethodAnnotationsToDataObjectRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     *
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            BelongsToMock::class,
            SilverstripeConstants::BELONGS_TO,
            [
                'BelongsToRelationship' => RelationMock::class . '.Parent',
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
