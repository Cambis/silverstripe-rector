<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector;
use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

// See: https://docs.silverstripe.org/en/5/changelogs/5.3.0/
return static function (RectorConfig $rectorConfig): void {
    // Add Silverstripe53 PHPStan patch
    $rectorConfig->phpstanConfigs([
        SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH,
        SilverstripeOption::PHPSTAN_FOR_RECTOR_SILVERSTRIPE_53_PATH,
    ]);

    $rectorConfig->import(__DIR__ . '/../config.php');

    // https://github.com/silverstripe/silverstripe-framework/pull/11236
    $rectorConfig->rule(FieldListFieldsToTabDeprecatedNonArrayArgumentRector::class);

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

    // https://github.com/symbiote/silverstripe-queuedjobs/commit/b6c1c4ffe3f4a577bf98cfcac4a7fb8fba94c0c0
    $rectorConfig->rule(ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector::class);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        // https://github.com/silverstripe/silverstripe-elemental/commit/d3cbca700a8d1eb80a8ae8d8a2cf1a5e8ee0cb8f
        new MethodCallRename('DNADesign\Elemental\Models\BaseElement', 'getDescription', 'i18n_classDescription'),
    ]);
};
