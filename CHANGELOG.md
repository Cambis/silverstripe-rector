# silverstripe-rector

## 2.0.0

### Major Changes

- 1b66e2e: Rector 2.0 upgrade

### Minor Changes

- c7d7071: Add RenameConfigurationPropertyRector
- b8ec5df: Fix for infinite loop with RenameClassRector

### Patch Changes

- 5a4c6ca: Update Silverstripe 6 set to include renamed SiteTree properties
- 366e851: Address typo in default classname ControllerHasCurrToInstanceofRector

## 1.4.0

### Minor Changes

- 5354f4a: Upgrade dev dependencies
- c31a51a: Update Silverstripe 6.0 set

## 1.3.0

### Minor Changes

- c47e754: Drop silverstripe/framework and silverstripe/cms from dev dependencies
- 3baa08c: Add Silverstripe 5.4 set

## 1.2.0

### Minor Changes

- 458217b: Add migration rectors for sheadawson/silverstripe-linkable and legacy silverstripe/linkfield

## 1.1.0

### Minor Changes

- 90417f5: Add rectors to migrate gorriecoe link to silverstripe linkfield

## 1.0.0

### Major Changes

- 2f2de72:
  - Remove deprecated code as of 0.8.0
  - Drop silverstripe/framework as a dependency
  - Drop webmozart/assert as a dependency

## 0.8.3

### Patch Changes

- 1b4e970: Use RelatedConfigInterface to import required services

## 0.8.2

### Patch Changes

- e403a5a: Update e2e test configuration

## 0.8.1

### Patch Changes

- 4473f59: Address regression with vendormodules

## 0.8.0

### Minor Changes

- 742fc00: Use cambis/silverstan services, deprecate legacy services.

## 0.7.0

### Minor Changes

- 0ae8477: Add PHPStan bleeding edge
- fb999d0: Update Silverstripe 53 set

### Patch Changes

- 98d61c5: Fix for infinite loop with RenameClassRector
- 44419e9: Fix for array like comparison in FieldListFieldsToTabNonArrayToArrayArgumentRector

## 0.6.0

### Minor Changes

- fc39f46: Add generated test files to Silverstripe manifest
- 1193d89: Add nullable types
- 9453436: Add setlists up to Silverstripe 6.0
- cf4a3e3: - Use the Injector API to resolve classnames
  - Update extension owner tests to use true types rather than mocked types

### Patch Changes

- 8e692e1: Only include `\SilverStripe\Dev\TestOnly` classes during testing

## 0.5.1

### Patch Changes

- 6d48a9e: Resolve suffixed extension names

## 0.5.0

### Minor Changes

- e7b7209: Remove silverstripe/cms from dependencies

## 0.4.2

### Patch Changes

- 5c2930c: Add webmozart/assert

## 0.4.1

### Patch Changes

- 927e47c: Include missing ConfigurationPropertyTypeResolverInterface in Silverstripe50 and Silverstripe51 sets

## 0.4.0

### Minor Changes

- 6e058fa: Refactor common services

## 0.3.3

### Patch Changes

- 2a85e57: Run rector process
- a70b6c9: Ensure global vars are set
- 8c0fde8: Use FQN name for extension type mapping

## 0.3.2

### Patch Changes

- Update README

## 0.3.1

### Patch Changes

- e445c2a: Add AddExtendsAnnotationToContentControllerRector to Silverstripe52 set

## 0.3.0

### Minor Changes

- 17c28a1: Add services, add constants to file locations
- 91a8b6c: Refactor AbstractAddAnnotationsRector
- 4e40784:
  - Add author to namespace
  - Remove AddConfigAnnotationToConfigurablePropertyRector
  - Add APIAwareRector for safer access to the Silverstripe APIs
- 17c28a1: Add AddExtendsAnnotationToContentControllerRector
- 7fd06a6:
  - Add RenameEnabledToIsEnabledOnBuildTaskRector
  - Update README

## 0.2.1

### Patch Changes

- Set ViewableData as the base class for checking extensions

## 0.2.0

### Minor Changes

- Update docs to use new config format
- Update docs to include guide for how to resolve common issues

## 0.1.0

### Minor Changes

- Initial release
