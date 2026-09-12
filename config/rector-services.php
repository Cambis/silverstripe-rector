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
use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Rector\Config\RectorConfig;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;

return static function (RectorConfig $rectorConfig): void {
    // Register annotation comparators
    $rectorConfig->singleton(ExtendsAnnotationComparator::class);
    $rectorConfig->singleton(MethodAnnotationComparator::class);
    $rectorConfig->singleton(MixinAnnotationComparator::class);
    $rectorConfig->singleton(PropertyAnnotationComparator::class);
    $rectorConfig->singleton(TemplateAnnotationComparator::class);

    $rectorConfig->singleton(AnnotationComparator::class, static function (RectorConfig $rectorConfig): AnnotationComparator {
        return new AnnotationComparator([
            $rectorConfig->make(ExtendsAnnotationComparator::class),
            $rectorConfig->make(MethodAnnotationComparator::class),
            $rectorConfig->make(MixinAnnotationComparator::class),
            $rectorConfig->make(PropertyAnnotationComparator::class),
            $rectorConfig->make(TemplateAnnotationComparator::class),
        ]);
    });

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
