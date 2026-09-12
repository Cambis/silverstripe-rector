<?php

declare(strict_types=1);
use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameConfigurationPropertyRector;
use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameExtensionHookMethodRector;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameConfigurationProperty;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameExtensionHookMethod;
use Cambis\SilverstripeRector\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector;
use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector;
use Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector;
use Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector;
use Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Arguments\NodeAnalyzer\ArgumentAddingScope;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ArgumentAdderWithoutDefaultValue;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../config/config.php');

    // ==== silverstripe/framework:^5.1 =====

    $rectorConfig->rules([
        RenameEnabledToIsEnabledOnBuildTaskRector::class,
    ]);

    // ==== silverstripe/framework:^5.3 =====

    // https://github.com/silverstripe/silverstripe-framework/pull/11236
    $rectorConfig->rule(FieldListFieldsToTabDeprecatedNonArrayArgumentRector::class);

    $rectorConfig->ruleWithConfigurationComposerVersionBound(
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
        ],
        'silverstripe/framework',
        '>=5.1'
    );

    // https://github.com/symbiote/silverstripe-queuedjobs/commit/b6c1c4ffe3f4a577bf98cfcac4a7fb8fba94c0c0
    $rectorConfig->rule(ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector::class);

    $rectorConfig->ruleWithConfigurationComposerVersionBound(
        RenameMethodRector::class,
        [
            // https://github.com/silverstripe/silverstripe-elemental/commit/d3cbca700a8d1eb80a8ae8d8a2cf1a5e8ee0cb8f
            new MethodCallRename('DNADesign\Elemental\Models\BaseElement', 'getDescription', 'i18n_classDescription'),
        ],
        'silverstripe/framework',
        '>=5.3'
    );

    // ==== silverstripe/framework:^5.4 =====

    $rectorConfig->ruleWithConfigurationComposerVersionBound(MethodCallToStaticCallRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/6a3659d69d94742a11b7eaaa57558f85a2b0b343
        new MethodCallToStaticCall('SilverStripe\Logging\HTTPOutputHandler', 'isCli', 'SilverStripe\Control\Director', 'isCli'),
        // https://github.com/silverstripe/silverstripe-contentreview/commit/40e45cda53c414650ee9db415d344ab9f02dc371
        new MethodCallToStaticCall('SilverStripe\ContentReview\Tasks\ContentReviewEmails', 'isValidEmail', 'SilverStripe\Control\Email\Email', 'is_valid_email'),
        // https://github.com/silverstripe/silverstripe-subsites/commit/11f4ebcb45685660354fbc401b9675b96e69ba85
        new MethodCallToStaticCall('SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'ListSubsites', 'SilverStripe\Subsites\Extensions\LeftAndMainSubsites', 'SubsiteSwitchList'),
    ], 'silverstripe/framework', '>=5.4');

    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameMethodRector::class, [
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
    ], 'silverstripe/framework', '>=5.4');

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(ViewableDataCachedCallToObjRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/165f72fd222daa8f93a494cdad7d9ef66ffa20d1
    $rectorConfig->rule(SSViewerGetBaseTagRector::class);

    // https://github.com/silverstripe/silverstripe-framework/commit/5b16f7de82037e9363f5ff6402d40652dae42614
    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameConfigurationPropertyRector::class, [
        new RenameConfigurationProperty('SilverStripe\ORM\DataObject', 'description', 'class_description'),
    ], 'silverstripe/framework', '>=5.4');

    // https://github.com/silverstripe/silverstripe-framework/commit/15683cfd9839a2af012999e28a577592c6cdcab9
    $rectorConfig->rule(FormFieldExtendValidationResultToExtendRector::class);

    // https://github.com/silverstripe/silverstripe-asset-admin/commit/e8bd854105ec44de0e4b1432d081cc5fc0a77b07
    $rectorConfig->rule(RemoteFileModalExtensionGetMethodsRector::class);

    $rectorConfig->ruleWithConfigurationComposerVersionBound(ReplaceArgumentDefaultValueRector::class, [
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
    ], 'silverstripe/framework', '>=5.4');

    // ==== silverstripe/framework:^6.0 ====

    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameClassRector::class, [
        // https://github.com/silverstripe/silverstripe-elemental/pull/1254
        'DNADesign\Elemental\TopPage\DataExtension' => 'DNADesign\Elemental\Extensions\TopPageElementExtension',
        'DNADesign\Elemental\TopPage\FluentExtension' => 'DNADesign\Elemental\Extensions\TopPageElementFluentExtension',
        'DNADesign\Elemental\TopPage\SiteTreeExtension' => 'DNADesign\Elemental\Extensions\TopPageSiteTreeExtension',
        // https://github.com/silverstripe/silverstripe-framework/pull/11370
        'SilverStripe\ORM\ArrayLib' => 'SilverStripe\Core\ArrayLib',
        'SilverStripe\ORM\ValidationException' => 'SilverStripe\Core\Validation\ValidationException',
        'SilverStripe\ORM\ValidationResult' => 'SilverStripe\Core\Validation\ValidationResult',
        'SilverStripe\View\ArrayData' => 'SilverStripe\Model\ArrayData',
        'SilverStripe\ORM\ArrayList' => 'SilverStripe\Model\List\ArrayList',
        'SilverStripe\ORM\Filterable' => 'SilverStripe\Model\List\Filterable',
        'SilverStripe\ORM\GroupedList' => 'SilverStripe\Model\List\GroupedList',
        'SilverStripe\ORM\Limitable' => 'SilverStripe\Model\List\Limitable',
        'SilverStripe\ORM\ListDecorator' => 'SilverStripe\Model\List\ListDecorator',
        'SilverStripe\ORM\Map' => 'SilverStripe\Model\List\Map',
        'SilverStripe\ORM\PaginatedList' => 'SilverStripe\Model\List\PaginatedList',
        'SilverStripe\ORM\SS_List' => 'SilverStripe\Model\List\SS_List',
        'SilverStripe\ORM\Sortable' => 'SilverStripe\Model\List\Sortable',
        'SilverStripe\View\ViewableData' => 'SilverStripe\Model\ModelData',
        'SilverStripe\View\ViewableData_Customised' => 'SilverStripe\Model\ModelDataCustomised',
        'SilverStripe\View\ViewableData_Debugger' => 'SilverStripe\Model\ModelDataDebugger',
        // https://github.com/silverstripe/silverstripe-framework/pull/11405
        'SilverStripe\View\SSViewer_BasicIteratorSupport' => 'SilverStripe\TemplateEngine\BasicIteratorSupport',
        'SilverStripe\View\SSTemplateParseException' => 'SilverStripe\TemplateEngine\Exception\SSTemplateParseException',
        'SilverStripe\View\SSTemplateParser' => 'SilverStripe\TemplateEngine\SSTemplateParser',
        'SilverStripe\View\SSViewer_Scope' => 'SilverStripe\TemplateEngine\ScopeManager',
        'SilverStripe\View\SSViewer_DataPresenter' => 'SilverStripe\TemplateEngine\ScopeManager',
        'SilverStripe\View\TemplateIteratorProvider' => 'SilverStripe\TemplateEngine\TemplateIteratorProvider',
        'SilverStripe\View\TemplateParser' => 'SilverStripe\TemplateEngine\TemplateParser',
        // https://github.com/silverstripe/silverstripe-reports/pull/204
        'SilverStripe\SecurityReport\Forms\GridFieldExportReportButton' => 'SilverStripe\Reports\SecurityReport\Forms\GridFieldExportReportButton',
        'SilverStripe\SecurityReport\Forms\GridFieldPrintReportButton' => 'SilverStripe\Reports\SecurityReport\Forms\GridFieldPrintReportButton',
        'SilverStripe\SecurityReport\MemberReportExtension' => 'SilverStripe\Reports\SecurityReport\MemberReportExtension',
        'SilverStripe\SecurityReport\UserSecurityReport' => 'SilverStripe\Reports\SecurityReport\UserSecurityReport',
        'SilverStripe\SiteWideContentReport\Form\GridFieldBasicContentReport' => 'SilverStripe\Reports\SiteWideContentReport\Form\GridFieldBasicContentReport',
        'SilverStripe\SiteWideContentReport\Model\SitewideContentTaxonomy' => 'SilverStripe\Reports\SiteWideContentReport\Model\SitewideContentTaxonomy',
        'SilverStripe\SiteWideContentReport\SitewideContentReport' => 'SilverStripe\Reports\SiteWideContentReport\SitewideContentReport',
        'SilverStripe\ExternalLinks\Controllers\CMSExternalLinksController' => 'SilverStripe\Reports\ExternalLinks\Controllers\CMSExternalLinksController',
        'SilverStripe\ExternalLinks\Jobs\CheckExternalLinksJob' => 'SilverStripe\Reports\ExternalLinks\Jobs\CheckExternalLinksJob',
        'SilverStripe\ExternalLinks\Model\BrokenExternalLink' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalLink',
        'SilverStripe\ExternalLinks\Model\BrokenExternalPageTrack' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalPageTrack',
        'SilverStripe\ExternalLinks\Model\BrokenExternalPageTrackStatus' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalPageTrackStatus',
        'SilverStripe\ExternalLinks\BrokenExternalLinksReport' => 'SilverStripe\Reports\ExternalLinks\Reports\BrokenExternalLinksReport',
        'SilverStripe\ExternalLinks\Tasks\CheckExternalLinksTask' => 'SilverStripe\Reports\ExternalLinks\Tasks\CheckExternalLinksTask',
        'SilverStripe\ExternalLinks\Tasks\CurlLinkChecker' => 'SilverStripe\Reports\ExternalLinks\Tasks\CurlLinkChecker',
        'SilverStripe\ExternalLinks\Tasks\LinkChecker' => 'SilverStripe\Reports\ExternalLinks\Tasks\LinkChecker',
        // https://github.com/silverstripe/silverstripe-framework/pull/11486
        'SilverStripe\Forms\Validator' => 'SilverStripe\Forms\Validation\Validator',
        'SilverStripe\Forms\RequiredFields' => 'SilverStripe\Forms\Validation\RequiredFieldsValidator',
        'SilverStripe\Forms\CompositeValidator' => 'SilverStripe\Forms\Validation\CompositeValidator',
        // https://github.com/silverstripe/silverstripe-userforms/pull/1353
        'SilverStripe\UserForms\Form\UserFormsRequiredFields' => 'SilverStripe\UserForms\Form\UserFormsRequiredFieldsValidator',
        // https://github.com/symbiote/silverstripe-advancedworkflow/pull/564
        'Symbiote\AdvancedWorkflow\Forms\AWRequiredFields' => 'Symbiote\AdvancedWorkflow\Forms\AWRequiredFieldsValidator',
    ], 'silverstripe/framework', '>=6.0');

    // https://docs.silverstripe.org/en/6/changelogs/6.0.0/#hooks-renamed
    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameExtensionHookMethodRector::class, [
        new RenameExtensionHookMethod('SilverStripe\Admin\LeftAndMain', 'init', 'onInit'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Controllers\CMSMain', 'updateLinkPageAdd', 'updateLinkRecordAdd'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Controllers\CMSMain', 'updateSiteTreeAsUL', 'updateTreeAsUL'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Controllers\CMSMain', 'updateSiteTreeHints', 'updateTreeHints'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Controllers\CMSMain', 'updateCurrentPageID', 'updateCurrentRecordID'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Model\SiteTree', 'MetaComponents', 'updateMetaComponents'),
        new RenameExtensionHookMethod('SilverStripe\CMS\Model\SiteTree', 'MetaTags', 'updateMetaTags'),
        new RenameExtensionHookMethod('SilverStripe\ErrorPage\ErrorPage', 'getDefaultRecords', 'updateDefaultRecords'),
        new RenameExtensionHookMethod('SilverStripe\ORM\DataObject', 'flushCache', 'onFlushCache'),
        new RenameExtensionHookMethod('SilverStripe\ORM\DataObject', 'populateDefaults', 'onAfterPopulateDefaults'),
        new RenameExtensionHookMethod('SilverStripe\ORM\DataObject', 'requireDefaultRecords', 'onRequireDefaultRecords'),
        new RenameExtensionHookMethod('SilverStripe\ORM\DataObject', 'validate', 'updateValidate'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'afterMemberLoggedIn', 'onAfterMemberLoggedIn'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'afterMemberLoggedOut', 'onAfterMemberLoggedOut'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'authenticationFailed', 'onAuthenticationFailed'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'authenticationFailedUnknownUser', 'onAuthenticationFailedUnknownUser'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'authenticationSucceeded', 'onAuthenticationSucceeded'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'beforeMemberLoggedIn', 'onBeforeMemberLoggedIn'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'beforeMemberLoggedOut', 'onBeforeMemberLoggedOut'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'registerFailedLogin', 'onRegisterFailedLogin'),
        new RenameExtensionHookMethod('SilverStripe\Security\MemberAuthenticator\LostPasswordHandler', 'forgotPassword', 'onForgotPassword'),
    ], 'silverstripe/framework', '>=6.0');

    // https://github.com/silverstripe/silverstripe-framework/pull/11613
    $rectorConfig->rule(ControllerHasCurrToInstanceofRector::class);

    $rectorConfig->ruleWithConfigurationComposerVersionBound(ArgumentAdderRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/pull/11300
        new ArgumentAdderWithoutDefaultValue('SilverStripe\Core\Injector\Factory', 'create', 0, 'service', new StringType(), ArgumentAddingScope::SCOPE_CLASS_METHOD),
    ], 'silverstripe/framework', '>=6.0');

    $rectorConfig->ruleWithConfigurationComposerVersionBound(AddReturnTypeDeclarationRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/pull/11300
        new AddReturnTypeDeclaration('SilverStripe\Core\Injector\Factory', 'create', TypeCombinator::addNull(new ObjectWithoutClassType())),
        // https://github.com/silverstripe/silverstripe-framework/commit/d033258edbc84fcb329ab70164d9d81abc81b408
        new AddReturnTypeDeclaration('SilverStripe\ORM\DataObject', 'validate', new ObjectType('SilverStripe\Core\Validation\ValidationResult')),
        // https://github.com/silverstripe/silverstripe-framework/commit/6b33b5a87510e065fd658a27cc300e16b2373ae4
        new AddReturnTypeDeclaration('SilverStripe\Model\ModelData', 'forTemplate', new StringType()),
        new AddReturnTypeDeclaration('SilverStripe\View\ViewableData', 'forTemplate', new StringType()),
    ], 'silverstripe/framework', '>=6.0');

    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameMethodRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/e3508d41d520983262e2fba28f7b6db0785e6d72
        new MethodCallRename('SilverStripe\ORM\DataObject', 'CMSEditLink', 'getCMSEditLink'),
        // https://github.com/silverstripe/silverstripe-elemental/commit/fde6be701703e1cb7e9f3efaed69b2ea34dd5a9b
        new MethodCallRename('DNADesign\Elemental\Models\BaseElement', 'getGraphQLTypeName', 'getTypeName'),
    ], 'silverstripe/framework', '>=6.0');

    $rectorConfig->ruleWithConfigurationComposerVersionBound(RenameConfigurationPropertyRector::class, [
        // https://github.com/silverstripe/silverstripe-cms/pull/3036
        new RenameConfigurationProperty('SilverStripe\CMS\Model\SiteTree', 'icon', 'cms_icon'),
        new RenameConfigurationProperty('SilverStripe\CMS\Model\SiteTree', 'icon_class', 'cms_icon_class'),
    ], 'silverstripe/framework', '>=6.0');

    // ==== silverstripe/framework^6.1 ====

    // https://github.com/silverstripe/silverstripe-framework/issues/11767
    $rectorConfig->rules([
        DataObjectGetByIdCachedRector::class,
        DataObjectDeleteByIdCachedRector::class,
        DataObjectGetOneCachedRector::class,
    ]);
};
