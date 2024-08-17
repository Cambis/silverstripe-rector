<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Testing\Fixture;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Injector\InjectorLoader;
use SilverStripe\Core\Manifest\ClassLoader;
use SilverStripe\Core\Manifest\ClassManifest;
use function dirname;

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

        // Create a new class manifest with the generated file
        $classManifest = new ClassManifest($basePath);
        $classManifest->handleFile($basePath, $inputFilePath, true);

        // Add the manifest to the class loader
        ClassLoader::inst()->pushManifest($classManifest, false);

        // Register any new classes with the Injector
        foreach (ClassInfo::classes_for_file($inputFilePath) as $class) {
            Injector::inst()
                ->load([$class])
                ->create($class);
        }
    }
}