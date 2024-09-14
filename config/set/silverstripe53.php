<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabNonArrayToArrayArgumentRector;
use Cambis\SilverstripeRector\Silverstripe53\TypeResolver\ConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

// See: https://docs.silverstripe.org/en/5/changelogs/5.3.0/
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->singleton(
        ConfigurationPropertyTypeResolverInterface::class,
        ConfigurationPropertyTypeResolver::class
    );

    // https://github.com/silverstripe/silverstripe-framework/pull/11236
    $rectorConfig->rule(FieldListFieldsToTabNonArrayToArrayArgumentRector::class);

    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            // https://github.com/silverstripe/silverstripe-framework/pull/11344
            'SilverStripe\Control\Util\IpUtils' => 'Symfony\Component\HttpFoundation\IpUtils',
            // https://github.com/silverstripe/silverstripe-admin/pull/1812
            'SilverStripe\Admin\LeftAndMainExtension' => 'SilverStripe\Core\Extension',
            // https://github.com/silverstripe/silverstripe-cms/pull/2991
            'SilverStripe\CMS\Model\SiteTreeExtension' => 'SilverStripe\Core\Extension',
            // https://github.com/silverstripe/silverstripe-framework/pull/11347
            'SilverStripe\ORM\DataExtension' => 'SilverStripe\Core\Extension',
        ]
    );
};
