# 33 Rules Overview

<br>

## Categories

- [CodeQuality](#codequality) (3)

- [LinkField](#linkfield) (5)

- [Renaming](#renaming) (2)

- [Silverstripe413](#silverstripe413) (9)

- [Silverstripe51](#silverstripe51) (1)

- [Silverstripe52](#silverstripe52) (6)

- [Silverstripe53](#silverstripe53) (2)

- [Silverstripe54](#silverstripe54) (4)

- [Silverstripe60](#silverstripe60) (1)

<br>

## CodeQuality

### DataObjectGetByIDCachedToUncachedRector

Change `DataObject::get_by_id()` to use `DataObject::get()->byID()` instead.

- class: [`Cambis\SilverstripeRector\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector`](../rules/CodeQuality/Rector/StaticCall/DataObjectGetByIDCachedToUncachedRector.php)

```diff
-$foo = \SilverStripe\Assets\File::get_by_id(1);
+$foo = \SilverStripe\Assets\File::get()->byID(1);
```

<br>

### InjectableNewInstanceToCreateRector

Change `new Injectable()` to use `Injectable::create()` instead.

- class: [`Cambis\SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector`](../rules/CodeQuality/Rector/New_/InjectableNewInstanceToCreateRector.php)

```diff
-$foo = new \SilverStripe\ORM\ArrayList();
+$foo = \SilverStripe\ORM\ArrayList::create();
```

<br>

### StaticPropertyFetchToConfigGetRector

Transforms static property fetch into `$this->config->get()`.

- class: [`Cambis\SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector`](../rules/CodeQuality/Rector/StaticPropertyFetch/StaticPropertyFetchToConfigGetRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static string $singular_name = 'Foo';

     public function getType(): string
     {
-        return self::$singular_name;
+        return $this->config()->get('singular_name');
     }
 }
```

<br>

## LinkField

### GorriecoeLinkFieldToSilverstripeLinkFieldRector

Migrate `gorriecoe\LinkField\LinkField` to `SilverStripe\LinkField\Form\LinkField`.

- class: [`Cambis\SilverstripeRector\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector`](../rules/LinkField/Rector/StaticCall/GorriecoeLinkFieldToSilverstripeLinkFieldRector.php)

```diff
-\gorriecoe\LinkField\LinkField::create('Link', 'Link', $this, ['types' => ['SiteTree']]);
+\SilverStripe\LinkField\Form\LinkField::create('Link', 'Link')
+    ->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class]);
```

<br>

### GorriecoeLinkToSilverstripeLinkRector

Migrate `gorriecoe\Link\Models\Link` configuration to `SilverStripe\LinkField\Models\Link` configuration.

- class: [`Cambis\SilverstripeRector\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector`](../rules/LinkField/Rector/Class_/GorriecoeLinkToSilverstripeLinkRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_one = [
-        'HasOneLink' => \gorriecoe\Link\Models\Link::class,
+        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
     ];

     private static array $has_many = [
-        'HasManyLinks' => \gorriecoe\Link\Models\Link::class,
+        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
+        'ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
     ];

-    private static array $many_many = [
-        'ManyManyLinks' => \gorriecoe\Link\Models\Link::class,
-    ];
-
-    private static array $many_many_extraFields = [
-        'ManyManyLinks' => [
-            'Sort' => 'Int',
-        ],
+    private static array $owns = [
+        'HasOneLink',
+        'HasManyLinks',
+        'ManyManyLinks',
     ];
 }
```

<br>

### SheadawsonLinkableFieldToSilverstripeLinkFieldRector

Migrate `Sheadawson\Linkable\Forms\LinkField` to `SilverStripe\LinkField\Form\LinkField`.

- class: [`Cambis\SilverstripeRector\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector`](../rules/LinkField/Rector/StaticCall/SheadawsonLinkableFieldToSilverstripeLinkFieldRector.php)

```diff
-\Sheadawson\Linkable\Forms\LinkField::create('LinkID', 'Link');
+\SilverStripe\LinkField\Form\LinkField::create('Link', 'Link');

-\SilverStripe\Forms\GridField\GridField::create('Links', 'Links', $this->Links());
+\SilverStripe\LinkField\Form\MultiLinkField::create('Links', 'Links');
```

<br>

### SheadawsonLinkableToSilverstripeLinkRector

Migrate `Sheadawson\Linkable\Models\Link` configuration to `SilverStripe\LinkField\Models\Link` configuration.

- class: [`Cambis\SilverstripeRector\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector`](../rules/LinkField/Rector/Class_/SheadawsonLinkableToSilverstripeLinkRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_one = [
-        'HasOneLink' => \Sheadawson\Linkable\Models\Link::class,
+        'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
     ];

     private static array $has_many = [
-        'HasManyLinks' => \Sheadawson\Linkable\Models\Link::class,
+        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
+        'ManyManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
     ];

-    private static array $many_many = [
-        'ManyManyLinks' => \Sheadawson\Linkable\Models\Link::class,
-    ];
-
-    private static array $many_many_extraFields = [
-        'ManyManyLinks' => [
-            'Sort' => 'Int',
-        ],
+    private static array $owns = [
+        'HasOneLink',
+        'HasManyLinks',
+        'ManyManyLinks',
     ];
 }
```

<br>

### SilverstripeLinkLegacyRector

Migrate legacy `SilverStripe\LinkField\Model\Link` configuration to `SilverStripe\LinkField\Models\Link` v4 configuration.

- class: [`Cambis\SilverstripeRector\LinkField\Rector\Class_\SilverstripeLinkLegacyRector`](../rules/LinkField/Rector/Class_/SilverstripeLinkLegacyRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_one = [
         'HasOneLink' => \SilverStripe\LinkField\Models\Link::class,
     ];

     private static array $has_many = [
-        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class,
+        'HasManyLinks' => \SilverStripe\LinkField\Models\Link::class . '.Owner',
+    ];
+
+    private static array $owns = [
+        'HasOneLink',
+        'HasManyLinks',
     ];
 }
```

<br>

## Renaming

### RenameConfigurationPropertyRector

Rename a configuration property.

:wrench: **configure it!**

- class: [`Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameConfigurationPropertyRector`](../rules/Renaming/Rector/Class_/RenameConfigurationPropertyRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
-    private static string $description = '';
+    private static string $class_description = '';
 }

-\SilverStripe\Config\Collections\MemoryConfigCollection::get('Foo', 'description');
-Foo::config()->get('description');
+\SilverStripe\Config\Collections\MemoryConfigCollection::get('Foo', 'class_description');
+Foo::config()->get('class_description');
```

<br>

### RenameExtensionHookMethodRector

Rename an extension hook method definition if the extension is applied to a given class. This rector only applies to instances of `SilverStripe\Core\Extension`, for all other use cases use `RenameMethodRector` instead.

:wrench: **configure it!**

- class: [`Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameExtensionHookMethodRector`](../rules/Renaming/Rector/Class_/RenameExtensionHookMethodRector.php)

```diff
 class FooExtension extends \SilverStripe\Core\Extension
 {
-    protected function updateDoSomething(): void
+    protected function updateDoSomethingElse(): void
     {
        // ...
     }
 }
```

<br>

## Silverstripe413

### AddBelongsManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddBelongsManyManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\ManyManyList|Bar[] Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $belongs_many_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method Bar Bar()
+ * @property int $BarID
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $belongs_to = [
         'Bar' => Bar::class . '.Parent',
     ];
 }
```

<br>

### AddDBFieldPropertyAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddDBFieldPropertyAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @property ?string $Bar
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $db = [
         'Bar' => 'Varchar(255)',
     ];
 }
```

<br>

### AddExtensionMixinAnnotationsToExtensibleRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector`](../rules/Silverstripe413/Rector/Class_/AddExtensionMixinAnnotationsToExtensibleRector.php)

```diff
+/**
+ * @mixin Bar
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $extensions = [
         Bar::class,
     ];
 }
```

<br>

### AddGetOwnerMethodAnnotationToExtensionRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector`](../rules/Silverstripe413/Rector/Class_/AddGetOwnerMethodAnnotationToExtensionRector.php)

```diff
+/**
+ * @method (Foo & static) getOwner()
+ */
 class FooExtension extends \SilverStripe\Core\Extension
 {
 }
```

<br>

### AddHasManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddHasManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\HasManyList|Bar[] Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### AddHasOnePropertyAndMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddHasOnePropertyAndMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method Bar Bar()
+ * @property int $BarID
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_one = [
         'Bar' => Bar::class,
     ];
 }
```

<br>

### AddManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddManyManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\ManyManyList|Bar[] Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $many_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### CompleteDynamicInjectablePropertiesRector

Add missing dynamic properties.

- class: [`Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector`](../rules/Silverstripe413/Rector/Class_/CompleteDynamicInjectablePropertiesRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
+    /**
+     * @var Bar
+     */
+    public $bar;
+
     private static array $dependencies = [
         'bar' => '%$' . Bar::class,
     ];
 }
```

<br>

## Silverstripe51

### RenameEnabledToIsEnabledOnBuildTaskRector

Rename protected property `$enabled` to configurable property `$is_enabled.`

- class: [`Cambis\SilverstripeRector\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector`](../rules/Silverstripe51/Rector/Class_/RenameEnabledToIsEnabledOnBuildTaskRector.php)

```diff
 class FooTask extends \SilverStripe\Dev\BuildTask
 {
-    protected $enabled = true;
+    private static bool $is_enabled = true;
 }
```

<br>

## Silverstripe52

### AddBelongsManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddBelongsManyManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\ManyManyList<Bar> Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $belongs_many_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### AddExtendsAnnotationToContentControllerRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector`](../rules/Silverstripe52/Rector/Class_/AddExtendsAnnotationToContentControllerRector.php)

```diff
 class Page extends \SilverStripe\ORM\Model\SiteTree
 {
 }

+/**
+ * @template T of Page
+ * @extends \SilverStripe\CMS\Controllers\ContentController<T>
+ */
 class PageController extends \SilverStripe\CMS\Controllers\ContentController
 {
 }

 class Homepage extends Page
 {
 }

+/**
+ * @extends PageController<Homepage>
+ */
 class HomepageController extends PageController
 {
 }
```

<br>

### AddExtendsAnnotationToExtensionRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector`](../rules/Silverstripe52/Rector/Class_/AddExtendsAnnotationToExtensionRector.php)

```diff
+/**
+ * @extends Extension<(Foo & static)>
+ */
 class FooExtension extends \SilverStripe\Core\Extension
 {
 }
```

<br>

### AddHasManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddHasManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\HasManyList<Bar> Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $has_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### AddManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddManyManyMethodAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @method \SilverStripe\ORM\ManyManyList<Bar> Bars()
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $many_many = [
         'Bars' => Bar::class,
     ];
 }
```

<br>

### RemoveGetOwnerMethodAnnotationFromExtensionsRector

Remove `getOwner()` method annotation.

- class: [`Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector`](../rules/Silverstripe52/Rector/Class_/RemoveGetOwnerMethodAnnotationFromExtensionsRector.php)

```diff
-/**
- * @method getOwner() $this
- */
 class Foo extends \SilverStripe\Core\Extension
 {
 }
```

<br>

## Silverstripe53

### FieldListFieldsToTabDeprecatedNonArrayArgumentRector

Rename `FieldList::addFieldsToTab()` and `FieldList::removeFieldsFromTab()` to `FieldList::addFieldToTab()` and `FieldList::removeFieldFromTab()` respectively if the second argument is not an array.

- class: [`Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\FieldListFieldsToTabDeprecatedNonArrayArgumentRector`](../rules/Silverstripe53/Rector/MethodCall/FieldListFieldsToTabDeprecatedNonArrayArgumentRector.php)

```diff
 \SilverStripe\Forms\FieldList::create()
-    ->addFieldsToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));
+    ->addFieldToTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));

 \SilverStripe\Forms\FieldList::create()
-    ->removeFieldsFromTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));
+    ->removeFieldFromTab('Root.Main', \SilverStripe\Forms\TextField::create('Field'));
```

<br>

### ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector

Migrate `ProcessJobQueueTask::getQueue()` to `AbstractQueuedJob::getQueue()`.

- class: [`Cambis\SilverstripeRector\Silverstripe53\Rector\MethodCall\ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector`](../rules/Silverstripe53/Rector/MethodCall/ProcessJobQueueTaskGetQueueToAbstractQueuedJobGetQueueRector.php)

```diff
 class FooTask extends \Symbiote\QueuedJobs\Tasks\ProcessJobQueueTask
 {
     public function run($request): void
     {
        // ...
-       $queue = $this->getQueue($request);
+       $queue = \Symbiote\QueuedJobs\Services\AbstractQueuedJob::getQueue($request->getVar('queue') ?? 'Queued');
        // ...
     }
 }
```

<br>

## Silverstripe54

### FormFieldExtendValidationResultToExtendRector

Migrate `FormField::extendValidationResult()` to `FormField::extend()`.

- class: [`Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector`](../rules/Silverstripe54/Rector/MethodCall/FormFieldExtendValidationResultToExtendRector.php)

```diff
 class FooField extends \SilverStripe\Forms\FormField
 {
     public function validate($validator): bool
     {
-        return $this->extendValidationResult(true, $validator);
+        $result = true;
+
+        $this->extend('updateValidationResult', true, $validator);
+
+        return $result;
     }
 }
```

<br>

### RemoteFileModalExtensionGetMethodsRector

Migrate `RemoteModalFileExtension::getRequest()` to `RemoteModalFileExtension::getOwner()->getRequest()` and `RemoteModalFileExtension::getSchemaResponse()` to `RemoteModalFileExtension::getOwner()->getSchemaResponse()`.

- class: [`Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector`](../rules/Silverstripe54/Rector/MethodCall/RemoteFileModalExtensionGetMethodsRector.php)

```diff
 class FooExtension extends \SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension
 {
     public function doSomething(): void
     {
-        $this->getRequest();
-        $this->getSchemaResponse('schema');
+        $this->getOwner()->getRequest();
+        $this->getOwner()->getSchemaRespons('schema');
     }
 }
```

<br>

### SSViewerGetBaseTagRector

Migrate `SSViewer::get_base_tag()` to `SSViewer::getBaseTag()`.

- class: [`Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector`](../rules/Silverstripe54/Rector/StaticCall/SSViewerGetBaseTagRector.php)

```diff
-\SilverStripe\View\SSViewer::get_base_tag('some content');
+\SilverStripe\View\SSViewer::getBaseTag(preg_match('/<!DOCTYPE[^>]+xhtml/i', 'some content') === 1);
```

<br>

### ViewableDataCachedCallToObjRector

Migrate `ViewableData::cachedCall()` to `ViewableData::obj()`.

- class: [`Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector`](../rules/Silverstripe54/Rector/MethodCall/ViewableDataCachedCallToObjRector.php)

```diff
-\SilverStripe\View\ViewableData::create()->cachedCall('Foo', [], null);
+\SilverStripe\View\ViewableData::create()->obj('Foo', [], true, null);
```

<br>

## Silverstripe60

### ControllerHasCurrToInstanceofRector

Migrate `Controller::has_curr()` check to `Controller::curr() instanceof Controller`.

- class: [`Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector`](../rules/Silverstripe60/Rector/StaticCall/ControllerHasCurrToInstanceofRector.php)

```diff
-if (\SilverStripe\Control\Controller::has_curr()) {
+if (\SilverStripe\Control\Controller::curr() instanceof \SilverStripe\Control\Controller) {
    // ...
 }
```

<br>
