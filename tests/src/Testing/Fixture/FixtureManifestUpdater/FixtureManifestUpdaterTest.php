<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater;

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Testing\Fixture\FixtureManifestUpdater;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Override;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ClassLoader;

final class FixtureManifestUpdaterTest extends AbstractRectorTestCase
{
    private ?string $inputFilePath = null;

    public function testAddInputFileToClassManifest(): void
    {
        $this->inputFilePath = __DIR__ . '/Fixture/foo.php';
        $numOfClasses = count(ClassInfo::allClasses());

        $expectedClassName = 'Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater\Fixture\Foo';
        $fixtureFilePath = __DIR__ . '/Fixture/foo.php.inc';

        FileSystem::write($this->inputFilePath, FileSystem::read($fixtureFilePath));
        FixtureManifestUpdater::addInputFileToClassManifest($this->inputFilePath);

        $this->assertArrayHasKey(Strings::lower($expectedClassName), ClassLoader::inst()->getManifest()->getClassNames());
        $this->assertTrue(ClassInfo::exists($expectedClassName));
        $this->assertCount($numOfClasses + 1, ClassInfo::allClasses());
        $this->assertTrue(Injector::inst()->has($expectedClassName));
        $this->assertInstanceOf($expectedClassName, Injector::inst()->create($expectedClassName));
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return SilverstripeSetList::WITH_SILVERSTRIPE_API;
    }

    #[Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->inputFilePath !== null) {
            FileSystem::delete($this->inputFilePath);
        }
    }
}
