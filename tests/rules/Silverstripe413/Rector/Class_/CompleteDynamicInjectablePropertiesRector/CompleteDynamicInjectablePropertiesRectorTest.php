<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;

use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture\Injectable;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture\InjectableComplete;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Fixture\InjectableInterface;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyInterface;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyInterfaceImplementor;
use Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector\Source\DependencyMock;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Iterator;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;

final class CompleteDynamicInjectablePropertiesRectorTest extends AbstractRectorTestCase
{
    /**
     * Load properties via {@see SilverStripe\Core\Config\Config::modify()} in order for this to work in this testing environment.
     */
    #[Override]
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

        Injector::inst()->registerService(
            new DependencyInterfaceImplementor(),
            DependencyInterface::class
        );

        Config::modify()->merge(
            InjectableInterface::class,
            SilverstripeConstants::PROPERTY_DEPENDENCIES,
            [
                'dependency' => '%$' . DependencyInterface::class,
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
