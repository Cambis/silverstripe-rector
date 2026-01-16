<?php

declare(strict_types=1);

use Cambis\Silverstan\Autoloader\Autoloader;
use Cambis\Silverstan\ClassManifest\ClassManifest;
use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\FileFinder\FileFinder;
use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator\ExtendsAnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator\MethodAnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator\MixinAnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator\PropertyAnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\AnnotationComparator\TemplateAnnotationComparator;
use Cambis\SilverstripeRector\AnnotationComparator\Contract\AnnotationComparatorInterface;
use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Rector\Config\RectorConfig;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;

return static function (RectorConfig $rectorConfig): void {
    // Register annotation comparators
    $rectorConfig->autotagInterface(AnnotationComparatorInterface::class);
    $rectorConfig->singleton(ExtendsAnnotationComparator::class);
    $rectorConfig->singleton(MethodAnnotationComparator::class);
    $rectorConfig->singleton(MixinAnnotationComparator::class);
    $rectorConfig->singleton(PropertyAnnotationComparator::class);
    $rectorConfig->singleton(TemplateAnnotationComparator::class);

    /** @phpstan-ignore-next-line */
    $rectorConfig->when(AnnotationComparator::class)->needs('$annotationComparators')->giveTagged(AnnotationComparatorInterface::class);

    // Register Silverstan services
    $rectorConfig->phpstanConfig(SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH);

    $silverstanServices = [
        Autoloader::class,
        ClassManifest::class,
        ConfigurationResolver::class,
        FileFinder::class,
        TypeResolver::class,
    ];

    foreach ($silverstanServices as $silverstanService) {
        $rectorConfig->singleton($silverstanService, static function (RectorConfig $rectorConfig) use ($silverstanService) {
            $phpStanServicesFactory = $rectorConfig->make(PHPStanServicesFactory::class);

            return $phpStanServicesFactory->getByType($silverstanService);
        });
    }

    // Register our autoloader
    $rectorConfig->make(Autoloader::class)->register();
};
