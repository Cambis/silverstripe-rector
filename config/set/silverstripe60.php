<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameExtensionHookMethodRector;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameExtensionHookMethod;
use Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Arguments\NodeAnalyzer\ArgumentAddingScope;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\ValueObject\ArgumentAdderWithoutDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

// https://docs.silverstripe.org/en/6/changelogs/6.0.0/
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
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
        // https://github.com/silverstripe/silverstripe-framework/pull/11405/files
        'SilverStripe\View\SSViewer_BasicIteratorSupport' => 'SilverStripe\TemplateEngine\BasicIteratorSupport',
        'SilverStripe\View\SSTemplateParseException' => 'SilverStripe\TemplateEngine\Exception\SSTemplateParseException',
        'SilverStripe\View\SSTemplateParser' => 'SilverStripe\TemplateEngine\SSTemplateParser',
        'SilverStripe\View\SSViewer_Scope' => 'SilverStripe\TemplateEngine\ScopeManager',
        'SilverStripe\View\SSViewer_DataPresenter' => 'SilverStripe\TemplateEngine\ScopeManager',
        'SilverStripe\View\TemplateIteratorProvider' => 'SilverStripe\TemplateEngine\TemplateIteratorProvider',
        'SilverStripe\View\TemplateParser' => 'SilverStripe\TemplateEngine\TemplateParser',
        // TODO: add link to PR
        'SilverStripe\SecurityReport\Forms\GridFieldExportReportButton' => 'SilverStripe\Reports\SecurityReport\Forms\GridFieldExportReportButton',
        'SilverStripe\SecurityReport\Forms\GridFieldPrintReportButton' => 'SilverStripe\Reports\SecurityReport\Forms\GridFieldPrintReportButton',
        'SilverStripe\SecurityReport\MemberReportExtension' => 'SilverStripe\Reports\SecurityReport\MemberReportExtension',
        'SilverStripe\SecurityReport\UserSecurityReport' => 'SilverStripe\Reports\SecurityReport\UserSecurityReport',
        // TODO: add link to PR
        'SilverStripe\SiteWideContentReport\Form\GridFieldBasicContentReport' => 'SilverStripe\Reports\SiteWideContentReport\Form\GridFieldBasicContentReport',
        'SilverStripe\SiteWideContentReport\Model\SitewideContentTaxonomy' => 'SilverStripe\Reports\SiteWideContentReport\Model\SitewideContentTaxonomy',
        'SilverStripe\SiteWideContentReport\SitewideContentReport' => 'SilverStripe\Reports\SiteWideContentReport\SitewideContentReport',
        // TODO: add link to PR
        'SilverStripe\ExternalLinks\Controllers\CMSExternalLinksController' => 'SilverStripe\Reports\ExternalLinks\Controllers\CMSExternalLinksController',
        'SilverStripe\ExternalLinks\Jobs\CheckExternalLinksJob' => 'SilverStripe\Reports\ExternalLinks\Jobs\CheckExternalLinksJob',
        'SilverStripe\ExternalLinks\Model\BrokenExternalLink' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalLink',
        'SilverStripe\ExternalLinks\Model\BrokenExternalPageTrack' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalPageTrack',
        'SilverStripe\ExternalLinks\Model\BrokenExternalPageTrackStatus' => 'SilverStripe\Reports\ExternalLinks\Model\BrokenExternalPageTrackStatus',
        'SilverStripe\ExternalLinks\BrokenExternalLinksReport' => 'SilverStripe\Reports\ExternalLinks\Reports\BrokenExternalLinksReport',
        'SilverStripe\ExternalLinks\Tasks\CheckExternalLinksTask' => 'SilverStripe\Reports\ExternalLinks\Tasks\CheckExternalLinksTask',
        'SilverStripe\ExternalLinks\Tasks\CurlLinkChecker' => 'SilverStripe\Reports\ExternalLinks\Tasks\CurlLinkChecker',
        'SilverStripe\ExternalLinks\Tasks\LinkChecker' => 'SilverStripe\Reports\ExternalLinks\Tasks\LinkChecker',
        // TODO: add link to PR
        'SilverStripe\Forms\Validator' => 'SilverStripe\Forms\Validation\Validator',
        'SilverStripe\Forms\RequiredFields' => 'SilverStripe\Forms\Validation\RequiredFieldsValidator',
        'SilverStripe\Forms\CompositeValidator' => 'SilverStripe\Forms\Validation\CompositeValidator',
        // TODO: add link to PR
        'SilverStripe\UserForms\Form\UserFormsRequiredFields' => 'SilverStripe\UserForms\Form\UserFormsRequiredFieldsValidator',
        // TODO: add link to PR
        'Symbiote\AdvancedWorkflow\Forms\AWRequiredFields' => 'Symbiote\AdvancedWorkflow\Forms\AWRequiredFieldsValidator',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameExtensionHookMethodRector::class, [
        // https://docs.silverstripe.org/en/6/changelogs/6.0.0/#hooks-renamed
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
        new RenameExtensionHookMethod('SilverStripe\Security\MemberAuthenticator\LostPasswordHandler', 'forgotPassword', 'onForgotPassword'),
        new RenameExtensionHookMethod('SilverStripe\Security\Member', 'registerFailedLogin', 'onRegisterFailedLogin'),
    ]);

    // https://github.com/silverstripe/silverstripe-framework/pull/11613
    $rectorConfig->rule(ControllerHasCurrToInstanceofRector::class);

    $rectorConfig->ruleWithConfiguration(ArgumentAdderRector::class, [
        new ArgumentAdderWithoutDefaultValue('SilverStripe\Core\Injector\Factory', 'create', 0, 'service', new StringType(), ArgumentAddingScope::SCOPE_CLASS_METHOD),
    ]);

    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
        new AddReturnTypeDeclaration('SilverStripe\Core\Injector\Factory', 'create', TypeCombinator::addNull(new ObjectWithoutClassType())),
    ]);
};
