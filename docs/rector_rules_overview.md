# 25 Rules Overview

<br>

## Categories

- [CodeQuality](#codequality) (3)

- [LinkField](#linkfield) (5)

- [Silverstripe413](#silverstripe413) (9)

- [Silverstripe51](#silverstripe51) (1)

- [Silverstripe52](#silverstripe52) (6)

- [Silverstripe53](#silverstripe53) (1)

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
