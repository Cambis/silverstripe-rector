<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\Visibility;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Visibility\ValueObject\ChangeMethodVisibility;

return static function (RectorConfig $rectorConfig): void {
    /**
     * @var list<string>
     * @see https://github.com/emteknetnz/extension-protector/blob/7e778ee94e1b487aeffdd928da39fca037bf1563/metadata.php#L46
     */
    $protectHookMethods = [
        'afterCallActionHandler',
        'afterCallActionURLHandler',
        'afterCallFormHandler',
        'afterFindOrCreateAdmin',
        'afterFindOrCreateDefaultAdmin',
        'afterGetAllLivePageURLs',
        'afterInsertBaseRows',
        'afterInsertTypeSpecificRows',
        'afterLogin',
        'afterLogout',
        'afterMigrateHasManyRelations',
        'afterMigrateManyManyRelations',
        'afterMigrateTitleColumn',
        'afterPerformMigration',
        'afterPerformReadonlyTransformation',
        'afterSetOwnerForHasOneLinks',
        'afterUpdateFormField',
        'afterUpdateSiteTreeRows',
        'alternateGetByLink',
        'alternateSiteConfig',
        'augmentAllChildrenIncludingDeleted',
        'augmentDataQueryCreation',
        'augmentDatabase',
        'augmentHydrateFields',
        'augmentLoadLazyFields',
        'augmentSQL',
        'augmentStageChildren',
        'augmentSyncLinkTracking',
        'augmentValidURLSegment',
        'augmentWrite',
        'augmentWriteDeletedVersion',
        'authenticationFailedUnknownUser',
        'authenticationSucceeded',
        'beforeCallActionHandler',
        'beforeCallActionURLHandler',
        'beforeCallFormHandler',
        'beforeFindOrCreateAdmin',
        'beforeFindOrCreateDefaultAdmin',
        'beforeGetAllLivePageURLs',
        'beforeInsertBaseRows',
        'beforeInsertTypeSpecificRows',
        'beforeLogin',
        'beforeLogout',
        'beforeMigrateHasManyRelations',
        'beforeMigrateManyManyRelations',
        'beforeMigrateTitleColumn',
        'beforePerformMigration',
        'beforeSetOwnerForHasOneLinks',
        'beforeUpdateFormField',
        'beforeUpdateSiteTreeRows',
        'cacheKeyComponent',
        'canLogIn',
        'contentcontrollerInit',
        'extendCanExecute',
        'failedLogin',
        'failedLogout',
        'getErrorRecordFor',
        'handleAccountReset',
        'memberAutoLoggedIn',
        'migrateHasOneForLinkSubclass',
        'modelascontrollerInit',
        'onActionComplete',
        'onActionStart',
        'onAfterApplyState',
        'onAfterArchive',
        'onAfterChangePassword',
        'onAfterClear',
        'onAfterCopyLocale',
        'onAfterDuplicate',
        'onAfterDuplicateToSubsite',
        'onAfterEndTestSession',
        'onAfterFormSaveInto',
        'onAfterGeneratePageResponse',
        'onAfterGenerateToken',
        'onAfterInit',
        'onAfterLDAPGroupSyncTask',
        'onAfterLDAPMemberSyncTask',
        'onAfterLoad',
        'onAfterLoadIntoFile',
        'onAfterLocalisedCopy',
        'onAfterLocalisedCopyRelation',
        'onAfterParse',
        'onAfterProcessAll',
        'onAfterProcessRecord',
        'onAfterPublish',
        'onAfterPublishRecursive',
        'onAfterRenewToken',
        'onAfterReorderItems',
        'onAfterRequireDefaultElementalRecords',
        'onAfterRestoreToStage',
        'onAfterRevertToLive',
        'onAfterRollbackRecursive',
        'onAfterRollbackSingle',
        'onAfterSave',
        'onAfterSend',
        'onAfterSetPassword',
        'onAfterSkippedWrite',
        'onAfterStartTestSession',
        'onAfterUnpublish',
        'onAfterUpdateTestSession',
        'onAfterUpload',
        'onAfterVersionDelete',
        'onAfterVersionedPublish',
        'onAfterWorkflowPublish',
        'onAfterWorkflowUnublish',
        'onAfterWriteToStage',
        'onBeforeApplyState',
        'onBeforeArchive',
        'onBeforeBuild',
        'onBeforeChangePassword',
        'onBeforeClear',
        'onBeforeCopyLocale',
        'onBeforeDuplicate',
        'onBeforeDuplicateToSubsite',
        'onBeforeEndTestSession',
        'onBeforeFormSaveInto',
        'onBeforeGeneratePageResponse',
        'onBeforeHTTPError',
        'onBeforeInit',
        'onBeforeJSONError',
        'onBeforeLocalisedCopy',
        'onBeforeLocalisedCopyRelation',
        'onBeforeManipulate',
        'onBeforeParse',
        'onBeforeProcessAll',
        'onBeforePublish',
        'onBeforePublishRecursive',
        'onBeforeRemoveLoginSession',
        'onBeforeRender',
        'onBeforeRenderHolder',
        'onBeforeReorderItems',
        'onBeforeRequireDefaultElementalRecords',
        'onBeforeRestoreToStage',
        'onBeforeRevertToLive',
        'onBeforeRollback',
        'onBeforeRollbackRecursive',
        'onBeforeRollbackSingle',
        'onBeforeSaveRequestToStore',
        'onBeforeSecurityLogin',
        'onBeforeSend',
        'onBeforeSetPassword',
        'onBeforeStartTestSession',
        'onBeforeUnpublish',
        'onBeforeUpdateTestSession',
        'onBeforeVersionedPublish',
        'onBeforeWriteToStage',
        'onBulkUpload',
        'onMethodVerificationFailure',
        'onMethodVerificationSuccess',
        'onPopulationFromField',
        'onPrepopulateTreeDataCache',
        'onRealMeLoginFailure',
        'onRealMeLoginSuccess',
        'onRegisterMethod',
        'onRegisterMethodFailure',
        'onSkipRegistration',
        'onTransition',
        'permissionDenied',
        'processHTML',
        'updateAbsoluteLink',
        'updateAfterProcess',
        'updateAlerts',
        'updateAllowedChildren',
        'updateAnchorAndQueryString',
        'updateAnchorsInContent',
        'updateAnchorsOnPage',
        'updateApplyVersionedFiltersAsConditions',
        'updateAttributes',
        'updateAvailableTypesForClass',
        'updateBadge',
        'updateBadgeLabel',
        'updateBadges',
        'updateBaseFields',
        'updateBatchActionsForm',
        'updateBlockSchema',
        'updateBreadcrumbs',
        'updateCMSActions',
        'updateCMSCompositeValidator',
        'updateCMSEditLink',
        'updateCMSTreeTitle',
        'updateCanAttachFileForRecipient',
        'updateCandidateAuthors',
        'updateCandidateUsers',
        'updateChangeSetResource',
        'updateChangeType',
        'updateChangedPasswordEmail',
        'updateCharacterSet',
        'updateCheckForBrokenLinks',
        'updateChildFilter',
        'updateClientConfig',
        'updateClientOptions',
        'updateColumns',
        'updateConfig',
        'updateContentForCmsSearch',
        'updateContentForSearchIndex',
        'updateCurrentPageID',
        'updateCurrentSiteConfig',
        'updateDataSchema',
        'updateDateFormat',
        'updateDefaultFrom',
        'updateDefaultLinkTitle',
        'updateDeleteTable',
        'updateDeleteTables',
        'updateDisownershipQuery',
        'updateDoAdd',
        'updateDropdownFilterOptions',
        'updateEditFormAfter',
        'updateEditLinkForDataObject',
        'updateEmail',
        'updateEmailData',
        'updateErrorFilename',
        'updateExcludedURLSegments',
        'updateExportButton',
        'updateExtraTreeTools',
        'updateFieldLabels',
        'updateFieldValidationOptions',
        'updateFilesInUse',
        'updateFilterDescription',
        'updateFilteredEmailRecipients',
        'updateFluentDirectorDefaultLocale',
        'updateFluentLocales',
        'updateFluentRoutes',
        'updateFluentRoutesForLocale',
        'updateForTemplateDefaultStyles',
        'updateForTemplateTemplate',
        'updateForgotPasswordEmail',
        'updateForm',
        'updateFormActions',
        'updateFormFields',
        'updateFormOptions',
        'updateFormScaffolder',
        'updateFormValidator',
        'updateFormatUrl',
        'updateFrontEndRequiredFields',
        'updateFrontendFormRequirements',
        'updateGeneratedThumbnails',
        'updateGetArchive',
        'updateGetBlogPosts',
        'updateGetData',
        'updateGetOptions',
        'updateGetTypes',
        'updateGetURL',
        'updateGetURLBeforeAnchor',
        'updateGetUrls',
        'updateGetVersionNumberByStage',
        'updateGrant',
        'updateGridField',
        'updateGridFieldConfig',
        'updateGroups',
        'updateGroupsFilter',
        'updateHintsCacheKey',
        'updateHomepageLink',
        'updateHydratedJob',
        'updateImportForm',
        'updateInheritableQueryParams',
        'updateIsArchived',
        'updateIsContributor',
        'updateIsEditor',
        'updateIsMigratable',
        'updateIsOnDraft',
        'updateIsPublished',
        'updateIsSiteUrl',
        'updateIsWriter',
        'updateItemEditForm',
        'updateItemRequestClass',
        'updateItemRequestHandler',
        'updateJobDescriptorAndJobOnCompletion',
        'updateJobDescriptorAndJobOnException',
        'updateJobDescriptorBeforeQueued',
        'updateJobDescriptorOnInitialisationException',
        'updateJoinTableName',
        'updateLinkPageAdd',
        'updateLinkWithSearch',
        'updateList',
        'updateListView',
        'updateLocalePageController',
        'updateLocalisationTabColumns',
        'updateLocalisationTabConfig',
        'updateLocaliseSelect',
        'updateLocaliseSelectDefault',
        'updateLoginAttempt',
        'updateManyManyComponents',
        'updateMemberFormFields',
        'updateMemberPasswordField',
        'updateMetaTitle',
        'updateMigratedElement',
        'updateName',
        'updateNeedsMigration',
        'updateNestedGroupsFilter',
        'updateNewLink',
        'updateNewRowClasses',
        'updateNodesFilter',
        'updateNotifyUsersCMSFields',
        'updateOnMessage',
        'updatePHP',
        'updatePackageInfo',
        'updatePage',
        'updatePageFilter',
        'updatePageOptions',
        'updatePageShouldSkip',
        'updateParameterFields',
        'updatePlaceholderGroups',
        'updatePopoverActions',
        'updatePostURL',
        'updatePrePopulateVersionNumberCache',
        'updatePreviewEnabled',
        'updatePreviewLink',
        'updatePrintData',
        'updatePrintExportColumns',
        'updateProgressForm',
        'updateRandomPassword',
        'updateReceivedFormSubmissionData',
        'updateRecordLocaleStagesDiffer',
        'updateRedirectionLink',
        'updateRenderTemplates',
        'updateRequestURL',
        'updateRequiredFields',
        'updateResetAccountForm',
        'updateResponse',
        'updateRestfulGetHandler',
        'updateRowAttributes',
        'updateRss',
        'updateRules',
        'updateSchema',
        'updateSchemaValidation',
        'updateSearchContext',
        'updateSearchForm',
        'updateSearchableFields',
        'updateSettingsFields',
        'updateShareTokenLink',
        'updateShouldDropColumn',
        'updateShouldMigrateColumn',
        'updateShouldPublishLink',
        'updateShouldPublishLinks',
        'updateSiteTreeAsUL',
        'updateSiteTreeExcludedClassNames',
        'updateSiteTreeHints',
        'updateSourceParams',
        'updateSourceRecords',
        'updateStagesDiffer',
        'updateStartForm',
        'updateStatusFlags',
        'updateStyleVariant',
        'updateSubmissionsGridField',
        'updateSummary',
        'updateSummaryFields',
        'updateTimeFormat',
        'updateTotp',
        'updateTrackedFormUpload',
        'updateTreeTitle',
        'updateURLSegment',
        'updateUsage',
        'updateUsageAncestorDataObjects',
        'updateUsageDataObject',
        'updateUsageExcludedClasses',
        'updateUsersFilter',
        'updateUsersWithIteratorFilter',
        'updateValidTransitions',
        'updateValidatePassword',
        'updateValidationResult',
        'updateValidator',
        'updateVersionModules',
        'updateView',
        'updateWorkflowEditForm',
        'updatelocaliseCondition',
        'updatelocaliseConditionDefault',
        'augmentNewSiteTreeItem',
        'accessedCMS',
        'updateCMSFields',
        'updateFrontEndFields',
        'onAfterDelete',
        'onAfterWrite',
        'onBeforeDelete',
        'onBeforeWrite',
    ];

    // Change the visiblity of hook methods to protected
    $rectorConfig->ruleWithConfiguration(
        ChangeMethodVisibilityRector::class,
        array_map(
            static function (string $hookMethodName): ChangeMethodVisibility {
                return new ChangeMethodVisibility('SilverStripe\Core\Extension', $hookMethodName, Visibility::PROTECTED);
            },
            $protectHookMethods
        )
    );
};
