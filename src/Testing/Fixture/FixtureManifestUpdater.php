<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Testing\Fixture;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Injector\InjectorLoader;
use SilverStripe\Core\Manifest\ClassLoader;
use function dirname;

/**
 * @see \Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater\FixtureManifestUpdaterTest
 */
final class FixtureManifestUpdater
{
    /**
     * Add the generated input file to the Silverstipe manifest, so any generated classes will be available to the Silverstripe API.
     */
    public static function addInputFileToClassManifest(string $inputFilePath): void
    {
        // We are not running Silverstripe so let's return
        if (InjectorLoader::inst()->countManifests() === 0) {
            return;
        }

        // Set the base path to the current directory, we only want to search files
        // in the fixture directory
        $basePath = dirname($inputFilePath);

        // Add the file to the current manifest
        ClassLoader::inst()
            ->getManifest()
            ->handleFile($basePath, $inputFilePath, true);

        // Register any new classes with the Injector
        foreach (ClassInfo::classes_for_file($inputFilePath) as $class) {
            Injector::inst()
                ->load([$class])
                ->create($class);
        }
    }
}