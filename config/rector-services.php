<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\ExtendsAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\MethodAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\MixinAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\PropertyAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\AnnotationComparator\TemplateAnnotationComparator;
use Cambis\SilverstripeRector\PhpDoc\Contract\AnnotationComparatorInterface;
use Rector\Config\RectorConfig;

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
};
