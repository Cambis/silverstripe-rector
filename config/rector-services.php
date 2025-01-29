<?php

declare(strict_types=1);

use Cambis\Silverstan\Autoloader\Autoloader;
use Cambis\Silverstan\ClassManifest\ClassManifest;
use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\ExtendsAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\MethodAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\MixinAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\PropertyAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\TemplateAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use Cambis\SilverstripeRector\Silverstripe413\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
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

    // Register base type resolver
    $rectorConfig->singleton(
        ConfigurationPropertyTypeResolverInterface::class,
        ConfigurationPropertyTypeResolver::class
    );

    // Register Silverstan services
    $rectorConfig->phpstanConfig(SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH);

    $silverstanServices = [
        Autoloader::class,
        ClassManifest::class,
        TypeResolver::class,
    ];

    foreach ($silverstanServices as $silverstanService) {
        $rectorConfig->singleton($silverstanService, static function (RectorConfig $rectorConfig) use ($silverstanService): mixed {
            $phpStanServicesFactory = $rectorConfig->make(PHPStanServicesFactory::class);

            return $phpStanServicesFactory->getByType($silverstanService);
        });
    }

    // Register our autoloader
    $rectorConfig->make(Autoloader::class)->register();
};
