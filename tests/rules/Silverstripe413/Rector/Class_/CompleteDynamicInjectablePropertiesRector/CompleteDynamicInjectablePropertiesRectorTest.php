<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture\Injectable;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture\InjectableComplete;
use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyMock;
use SilverstripeRector\ValueObject\SilverstripeConstants;

final class CompleteDynamicInjectablePropertiesRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::modify()->merge(
            Injectable::class,
            SilverstripeConstants::PROPERTY_DEPENDENCIES,
            [
                'dependency' => '%$' . DependencyMock::class,
                'message' => 'This is a message',
                'integer' => 1,
                'boolean' => true,
            ]
        );

        Config::modify()->merge(
            InjectableComplete::class,
            SilverstripeConstants::PROPERTY_DEPENDENCIES,
            [
                'dependency' => '%$' . DependencyMock::class,
                'message' => 'This is a message',
                'integer' => 1,
                'boolean' => true,
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
