<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector;
use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/6a3659d69d94742a11b7eaaa57558f85a2b0b343
        new MethodCallToStaticCall('SilverStripe\Logging\HTTPOutputHandler', 'isCli', 'SilverStripe\Control\Director', 'isCli'),
        // https://github.com/silverstripe/silverstripe-contentreview/commit/40e45cda53c414650ee9db415d344ab9f02dc371
        new MethodCallToStaticCall('SilverStripe\ContentReview\Tasks\ContentReviewEmails', 'isValidEmail', 'SilverStripe\Control\Email\Email', 'is_valid_email'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/8ec068f3fdfd8e062b5d54058bf629dbde034e0a
    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('SilverStripe\ORM\FieldType\DBField', 'defaultVal', 'getDefaultValue', 'setDefaultValue'),
    ]);

    // https://github.com/silverstripe/silverstripe-assets/commit/908c59b621c212cb1724c283807730458c8431ca
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\Assets\File', 'validate', 'validateFileName'),
    ]);

    // https://github.com/silverstripe/silverstripe-cms/commit/42aed2b72e2986207dc7469fd74824162bc5a03e
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\CMS\Controllers\ContentController', 'Menu', 'getMenu'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(ViewableDataCachedCallToObjRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(SSViewerGetBaseTagRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\View\SSViewer_Scope', 'getItem', 'getCurrentItem'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/2fb7cfa096d9e104ed4cc637a6601f72edee125e
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\ORM\FieldType\DBEnum', 'flushCache', 'reset'),
    ]);

    https://github.com/silverstripe/silverstripe-framework/commit/5b16f7de82037e9363f5ff6402d40652dae42614
    $rectorConfig->ruleWithConfiguration(RenamePropertyRector::class, [
        new RenameProperty('SilverStripe\CMS\Model\SiteTree', 'description', 'class_description'),
        new RenameProperty('DNADesign\Elemental\Models\BaseElement', 'description', 'class_description'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/15683cfd9839a2af012999e28a577592c6cdcab9
    $rectorConfig->rule(FormFieldExtendValidationResultToExtendRector::class);

    // https://github.com/silverstripe/silverstripe-subsites/commit/11f4ebcb45685660354fbc401b9675b96e69ba85
    $rectorConfig->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
        new MethodCallToStaticCall('SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'ListSubsites', 'SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'SubsiteSwitchList'),
    ]);

    // $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
    //     new MethodCallRename('SilverStripe\AssetAdmin\Extensions\RemoteFileModalExtension', 'getRequest', 'getOwner,getRequest'),
    // ]);

    // https://github.com/silverstripe/silverstripe-cms/commit/5c1f28ac701eae3b59bc30b30b90418d0a01d840
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPages', 'LinkRecords'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPagesWithSearch', 'LinkRecordsWithSearch'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageSettings', 'LinkRecordSettings'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageHistory', 'LinkRecordHistory'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageAdd', 'LinkRecordAdd'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageEdit', 'CMSEditLink'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'SiteTreeAsUL', 'TreeAsUL'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'getSiteTreeFor', 'getTreeFor'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'CanOrganiseSiteTree', 'canOrganiseTree'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/70ed6566b3ef9e894de834ee1d26ee13032803ff
    $rectorConfig->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'sessionMessage', 2, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'sessionError', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addError', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addFieldError', 4, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addMessage', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addFieldMessage', 4, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
    ]);
};
