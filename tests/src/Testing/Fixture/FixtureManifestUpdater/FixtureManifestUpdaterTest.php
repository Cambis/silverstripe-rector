<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater;

use Cambis\Silverstan\ClassManifest\ClassManifest;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Testing\Fixture\FixtureManifestUpdater;
use Cambis\SilverstripeRector\Testing\PHPUnit\AbstractSilverstripeRectorTestCase;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Override;
use function count;

final class FixtureManifestUpdaterTest extends AbstractSilverstripeRectorTestCase
{
    private ?string $inputFilePath = null;

    #[Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->inputFilePath !== null) {
            FileSystem::delete($this->inputFilePath);
        }
    }

    public function testAddInputFileToClassManifest(): void
    {
        $classManifest = $this->make(ClassManifest::class);

        $this->inputFilePath = __DIR__ . '/Fixture/foo.php';
        $numOfClasses = count($classManifest->getClasses());

        $expectedClassName = 'Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater\Fixture\Foo';
        $fixtureFilePath = __DIR__ . '/Fixture/foo.php.inc';

        FileSystem::write($this->inputFilePath, FileSystem::read($fixtureFilePath));
        FixtureManifestUpdater::addInputFileToClassManifest($this->inputFilePath, $classManifest);

        $this->assertArrayHasKey(Strings::lower($expectedClassName), $classManifest->getClasses());
        $this->assertTrue($classManifest->hasClass($expectedClassName));
        $this->assertCount($numOfClasses + 1, $classManifest->getClasses());
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }
}
