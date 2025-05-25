<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameConfigurationPropertyRector;
use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameExtensionHookMethodRector;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameConfigurationProperty;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameExtensionHookMethod;
use Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Arguments\NodeAnalyzer\ArgumentAddingScope;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\ValueObject\ArgumentAdderWithoutDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

// https://docs.silverstripe.org/en/6/changelogs/6.0.0/
return static function (RectorConfig $rectorConfig): void {
    // Add Silverstripe53 PHPStan patch
    $rectorConfig->phpstanConfigs([
        SilverstripeOption::PHPSTAN_FOR_RECTOR_PATH,
        SilverstripeOption::PHPSTAN_FOR_RECTOR_SILVERSTRIPE_53_PATH,
    ]);

    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
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
    ]);

    // https://docs.silverstripe.org/en/6/changelogs/6.0.0/#hooks-renamed
    $rectorConfig->ruleWithConfiguration(RenameExtensionHookMethodRector::class, [
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
    ]);

    // https://github.com/silverstripe/silverstripe-framework/pull/11613
    $rectorConfig->rule(ControllerHasCurrToInstanceofRector::class);

    $rectorConfig->ruleWithConfiguration(ArgumentAdderRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/pull/11300
        new ArgumentAdderWithoutDefaultValue('SilverStripe\Core\Injector\Factory', 'create', 0, 'service', new StringType(), ArgumentAddingScope::SCOPE_CLASS_METHOD),
    ]);

    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/pull/11300
        new AddReturnTypeDeclaration('SilverStripe\Core\Injector\Factory', 'create', TypeCombinator::addNull(new ObjectWithoutClassType())),
        // https://github.com/silverstripe/silverstripe-framework/commit/d033258edbc84fcb329ab70164d9d81abc81b408
        new AddReturnTypeDeclaration('SilverStripe\ORM\DataObject', 'validate', new ObjectType('SilverStripe\Core\Validation\ValidationResult')),
        // https://github.com/silverstripe/silverstripe-framework/commit/6b33b5a87510e065fd658a27cc300e16b2373ae4
        new AddReturnTypeDeclaration('SilverStripe\Model\ModelData', 'forTemplate', new StringType()),
        new AddReturnTypeDeclaration('SilverStripe\View\ViewableData', 'forTemplate', new StringType()),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        // https://github.com/silverstripe/silverstripe-framework/commit/e3508d41d520983262e2fba28f7b6db0785e6d72
        new MethodCallRename('SilverStripe\ORM\DataObject', 'CMSEditLink', 'getCMSEditLink'),
        // https://github.com/silverstripe/silverstripe-elemental/commit/fde6be701703e1cb7e9f3efaed69b2ea34dd5a9b
        new MethodCallRename('DNADesign\Elemental\Models\BaseElement', 'getGraphQLTypeName', 'getTypeName'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameConfigurationPropertyRector::class, [
        // https://github.com/silverstripe/silverstripe-cms/pull/3036
        new RenameConfigurationProperty('SilverStripe\CMS\Model\SiteTree', 'icon', 'cms_icon'),
        new RenameConfigurationProperty('SilverStripe\CMS\Model\SiteTree', 'icon_class', 'cms_icon_class'),
    ]);
};
