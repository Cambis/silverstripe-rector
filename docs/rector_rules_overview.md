# 18 Rules Overview

## AddBelongsManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddBelongsManyManyMethodAnnotationsToDataObjectRector.php)

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

## AddBelongsManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddBelongsManyManyMethodAnnotationsToDataObjectRector.php)

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

## AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector.php)

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

## AddConfigAnnotationToConfigurablePropertiesRector

Adds `@config` annotation to configurable properties for PHPStan.

- class: [`SilverstripeRector\PHPStan\Rector\Class_\AddConfigAnnotationToConfigurablePropertiesRector`](../rules/PHPStan/Rector/Class_/AddConfigAnnotationToConfigurablePropertiesRector.php)

```diff
 class Foo extends \SilverStripe\ORM\DataObject
 {
+    /**
+     * @config
+     */
     private static array $db = [
         'Bar' => 'Varchar(255)',
     ];
 }
```

<br>

## AddDBFieldPropertyAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddDBFieldPropertyAnnotationsToDataObjectRector.php)

```diff
+/**
+ * @property string $Bar
+ */
 class Foo extends \SilverStripe\ORM\DataObject
 {
     private static array $db = [
         'Bar' => 'Varchar(255)',
     ];
 }
```

<br>

## AddExtendsAnnotationToExtensionRector

Add missing dynamic annotations.

:wrench: **configure it!**

- class: [`SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector`](../rules/Silverstripe52/Rector/Class_/AddExtendsAnnotationToExtensionRector.php)

```diff
+/**
+ * @extends Extension<Foo&static>
+ */
 class FooExtension extends \SilverStripe\Core\Extension
 {
 }
```

<br>

## AddExtensionMixinAnnotationsToExtensibleRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector`](../rules/Silverstripe413/Rector/Class_/AddExtensionMixinAnnotationsToExtensibleRector.php)

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

## AddGetOwnerMethodAnnotationToExtensionRector

Add missing dynamic annotations.

:wrench: **configure it!**

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector`](../rules/Silverstripe413/Rector/Class_/AddGetOwnerMethodAnnotationToExtensionRector.php)

```diff
+/**
+ * @method Foo&static getOwner()
+ */
 class FooExtension extends \SilverStripe\Core\Extension
 {
 }
```

<br>

## AddHasManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddHasManyMethodAnnotationsToDataObjectRector.php)

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

## AddHasManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddHasManyMethodAnnotationsToDataObjectRector.php)

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

## AddHasOnePropertyAndMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddHasOnePropertyAndMethodAnnotationsToDataObjectRector.php)

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

## AddManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe413/Rector/Class_/AddManyManyMethodAnnotationsToDataObjectRector.php)

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

## AddManyManyMethodAnnotationsToDataObjectRector

Add missing dynamic annotations.

- class: [`SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector`](../rules/Silverstripe52/Rector/Class_/AddManyManyMethodAnnotationsToDataObjectRector.php)

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

## CompleteDynamicInjectablePropertiesRector

Add missing dynamic properties.

- class: [`SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector`](../rules/Silverstripe413/Rector/Class_/CompleteDynamicInjectablePropertiesRector.php)

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

## DataObjectGetByIDCachedToUncachedRector

Change `DataObject::get_by_id()` to use `DataObject::get()->byID()` instead.

- class: [`SilverstripeRector\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector`](../rules/CodeQuality/Rector/StaticCall/DataObjectGetByIDCachedToUncachedRector.php)

```diff
-$foo = \SilverStripe\Assets\File::get_by_id(1);
+$foo = \SilverStripe\Assets\File::get()->byID(1);
```

<br>

## InjectableNewInstanceToCreateRector

Change `new Injectable()` to use `Injectable::create()` instead.

- class: [`SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector`](../rules/CodeQuality/Rector/New_/InjectableNewInstanceToCreateRector.php)

```diff
-$foo = new \SilverStripe\ORM\ArrayList();
+$foo = \SilverStripe\ORM\ArrayList::create();
```

<br>

## RemoveGetOwnerMethodAnnotationFromExtensionsRector

Remove `getOwner()` method annotation.

- class: [`SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector`](../rules/Silverstripe52/Rector/Class_/RemoveGetOwnerMethodAnnotationFromExtensionsRector.php)

```diff
-/**
- * @method getOwner() $this
- */
 class Foo extends \SilverStripe\Core\Extension
 {
 }
```

<br>

## StaticPropertyFetchToConfigGetRector

Transforms static property fetch into `$this->config->get()`.

- class: [`SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector`](../rules/CodeQuality/Rector/StaticPropertyFetch/StaticPropertyFetchToConfigGetRector.php)

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
