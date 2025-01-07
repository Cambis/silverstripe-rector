<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Testing\Fixture;

use Cambis\Silverstan\ClassManifest\ClassManifest;
use Composer\ClassMapGenerator\PhpFileParser;

/**
 * @see \Cambis\SilverstripeRector\Tests\Testing\Fixture\FixtureManifestUpdater\FixtureManifestUpdaterTest
 */
final class FixtureManifestUpdater
{
    /**
     * Add the generated input file to the Silverstipe manifest, so any generated classes will be available to the Silverstripe API.
     *
     * @param non-empty-string $inputFilePath
     */
    public static function addInputFileToClassManifest(string $inputFilePath, ?ClassManifest $classManifest): void
    {
        if (!$classManifest instanceof ClassManifest) {
            return;
        }

        $classNames = PhpFileParser::findClasses($inputFilePath);

        foreach ($classNames as $className) {
            $classManifest->addClass($className, $inputFilePath);
        }
    }
}
