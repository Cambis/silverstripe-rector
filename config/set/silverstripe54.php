<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameConfigurationPropertyRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector;
use Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture\RenameConfigurationProperty;
use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;

// See: https://docs.silverstripe.org/en/5/changelogs/5.4.0/
return static function (RectorConfig $rectorConfig): void {
    // Add Silverstripe53 PHPStan patch
    $rectorConfig->phpstanConfigs([
        SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH,
        SilverstripeOption::PHPSTAN_FOR_RECTOR_SILVERSTRIPE_53_PATH,
    ]);

    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/6a3659d69d94742a11b7eaaa57558f85a2b0b343
        new MethodCallToStaticCall('SilverStripe\Logging\HTTPOutputHandler', 'isCli', 'SilverStripe\Control\Director', 'isCli'),
        // https://github.com/silverstripe/silverstripe-contentreview/commit/40e45cda53c414650ee9db415d344ab9f02dc371
        new MethodCallToStaticCall('SilverStripe\ContentReview\Tasks\ContentReviewEmails', 'isValidEmail', 'SilverStripe\Control\Email\Email', 'is_valid_email'),
        // https://github.com/silverstripe/silverstripe-subsites/commit/11f4ebcb45685660354fbc401b9675b96e69ba85
        new MethodCallToStaticCall('SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'ListSubsites', 'SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'SubsiteSwitchList'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/8ec068f3fdfd8e062b5d54058bf629dbde034e0a
    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('SilverStripe\ORM\FieldType\DBField', 'defaultVal', 'getDefaultValue', 'setDefaultValue'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        // https://github.com/silverstripe/silverstripe-assets/commit/908c59b621c212cb1724c283807730458c8431ca
        new MethodCallRename('SilverStripe\Assets\File', 'validate', 'validateFileName'),
        // https://github.com/silverstripe/silverstripe-cms/commit/42aed2b72e2986207dc7469fd74824162bc5a03e
        new MethodCallRename('SilverStripe\CMS\Controllers\ContentController', 'Menu', 'getMenu'),
        // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
        new MethodCallRename('SilverStripe\View\SSViewer_Scope', 'getItem', 'getCurrentItem'),
        // https://github.com/silverstripe/silverstripe-framework/commit/2fb7cfa096d9e104ed4cc637a6601f72edee125e
        new MethodCallRename('SilverStripe\ORM\FieldType\DBEnum', 'flushCache', 'reset'),
        // https://github.com/silverstripe/silverstripe-admin/commit/0517656dbb9c6ba38522a11c379a64c11f640162
        new MethodCallRename('SilverStripe\Admin\LeftAndMain', 'currentPageID', 'currentRecordID'),
        new MethodCallRename('SilverStripe\Admin\LeftAndMain', 'setCurrentPageID', 'setCurrentRecordID'),
        new MethodCallRename('SilverStripe\Admin\LeftAndMain', 'currentPage', 'currentRecord'),
        new MethodCallRename('SilverStripe\Admin\LeftAndMain', 'isCurrentPage', 'isCurrentRecord'),
        // https://github.com/silverstripe/silverstripe-cms/commit/5c1f28ac701eae3b59bc30b30b90418d0a01d840
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPages', 'LinkRecords'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPagesWithSearch', 'LinkRecordsWithSearch'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageSettings', 'LinkRecordSettings'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageHistory', 'LinkRecordHistory'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageAdd', 'LinkRecordAdd'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'LinkPageEdit', 'CMSEditLink'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'SiteTreeAsUL', 'TreeAsUL'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'getSiteTreeFor', 'getTreeFor'),
        new MethodCallRename('SilverStripe\CMS\Controllers\CMSMain', 'CanOrganiseSiteTree', 'canOrganiseTree'),
        // https://github.com/silverstripe/silverstripe-cms/commit/5c1f28ac701eae3b59bc30b30b90418d0a01d840
        new MethodCallRename('SilverStripe\CMS\Controllers\LeftAndMainPageIconsExtension', 'generatePageIconsCss', 'generateRecordIconsCss'),
        // https://github.com/silverstripe/silverstripe-framework/commit/f51b4f7c39a02c93cf4cc72c77b229397b04350c
        new MethodCallRename('SilverStripe\Forms\Form', 'validationResult', 'validate'),
        // https://github.com/silverstripe/silverstripe-framework/commit/06240b62fa3681707139b66c18cbcad182bbcd82
        new MethodCallRename('SilverStripe\Control\Director', 'get_session_environment_type', 'get_environment_type'),
        // https://github.com/silverstripe/silverstripe-framework/commit/9b13feead4036b6251f97a44306722ea20a98c56
        new MethodCallRename('SilverStripe\ORM\ListDecorator', 'TotalItems', 'getTotalItems'),
        new MethodCallRename('SilverStripe\ORM\PaginatedList', 'TotalItems', 'getTotalItems'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(ViewableDataCachedCallToObjRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(SSViewerGetBaseTagRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/5b16f7de82037e9363f5ff6402d40652dae42614
    $rectorConfig->ruleWithConfiguration(RenameConfigurationPropertyRector::class, [
        new RenameConfigurationProperty('SilverStripe\ORM\DataObject', 'description', 'class_description'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/commit/15683cfd9839a2af012999e28a577592c6cdcab9
    $rectorConfig->rule(FormFieldExtendValidationResultToExtendRector::class);

    // https://github.com/silverstripe/silverstripe-asset-admin/commit/e8bd854105ec44de0e4b1432d081cc5fc0a77b07
    $rectorConfig->rule(RemoteFileModalExtensionGetMethodsRector::class);

    $rectorConfig->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/3518d8ae0349929b4f1e843e3629a2120c84405a
        new ReplaceArgumentDefaultValue('SilverStripe\Forms\Form', 'loadDataFrom', 1, true, 'SilverStripe\Forms\Form::MERGE_CLEAR_MISSING'),
        new ReplaceArgumentDefaultValue('SilverStripe\Forms\Form', 'loadDataFrom', 1, false, 0),
        // https://github.com/silverstripe/silverstripe-framework/commit/70ed6566b3ef9e894de834ee1d26ee13032803ff
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'sessionMessage', 2, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'sessionError', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addError', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addFieldError', 4, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addMessage', 3, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
        new ReplaceArgumentDefaultValue('SilverStripe\ORM\ValidationResult', 'addFieldMessage', 4, null, 'SilverStripe\ORM\ValidationResult::CAST_TEXT'),
    ]);
};
