<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver;

use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Exception;
use Override;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use function array_unique;
use function explode;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function preg_match;
use function str_contains;

/**
 * This class contains common abstractions which can be used from Silverstripe 4.13 and up.
 */
abstract class AbstractConfigurationPropertyTypeResolver implements ConfigurationPropertyTypeResolverInterface
{
    /**
     * @var string
     * @see https://regex101.com/r/ZXIMlR/1
     */
    protected const EXTENSION_CLASSNAME_REGEX = '/^([^(]*)/';

    public function __construct(
        protected readonly ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    #[Override]
    public function resolvePropertyTypesFromDBFields(string $className): array
    {
        $properties = [];
        $db = $this->getConfig($className, SilverstripeConstants::PROPERTY_DB);

        if (!is_array($db) || $db === []) {
            return $properties;
        }

        foreach ($db as $fieldName => $fieldType) {
            $properties['$' . $fieldName] = $this->resolveDBFieldType($className, $fieldName, $fieldType);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_TO|SilverstripeConstants::PROPERTY_HAS_ONE $relationName
     * @return Type[]
     */
    #[Override]
    public function resolvePropertyTypesFromSingleRelation(string $className, string $relationName): array
    {
        $properties = [];
        $relation = $this->getConfig($className, $relationName);

        if ($relation === null) {
            return $properties;
        }

        foreach ($relation as $fieldName => $_) {
            $properties['$' . $fieldName . 'ID'] = new IntegerType();
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_TO|SilverstripeConstants::PROPERTY_HAS_ONE $relationName
     * @return Type[]
     */
    #[Override]
    public function resolveMethodTypesFromSingleRelation(string $className, string $relationName): array
    {
        $properties = [];
        $relation = $this->getConfig($className, $relationName);

        if ($relation === null) {
            return $properties;
        }

        foreach ($relation as $fieldName => $fieldType) {
            $properties[$fieldName] = $this->resolveRelationFieldType($fieldType);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    #[Override]
    public function resolvePropertyTypesFromDependencies(string $className): array
    {
        $properties = [];

        $dependencies = $this->getConfig($className, SilverstripeConstants::PROPERTY_DEPENDENCIES);

        if (!is_array($dependencies) || $dependencies === []) {
            return $properties;
        }

        foreach ($dependencies as $fieldName => $fieldType) {
            $properties[$fieldName] = $this->resolveDependencyFieldType($fieldType);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    #[Override]
    public function resolveMixinTypesFromExtensions(string $className): array
    {
        $properties = [];
        $extensions = $this->getConfig($className, SilverstripeConstants::PROPERTY_EXTENSIONS) ?? [];

        if ($extensions === []) {
            return $properties;
        }

        foreach (array_unique($extensions) as $extension) {
            $extensionClassName = $this->resolveExtensionClassName($extension);

            if ($extensionClassName === null) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($extensionClassName);

            if (!$classReflection->isSubclassOf(Extension::class)) {
                continue;
            }

            $properties[] = new FullyQualifiedObjectType($extensionClassName);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @param SilverstripeConstants::* $name
     */
    protected function getConfig(string $className, string $name): mixed
    {
        return Config::inst()->get($className, $name, Config::EXCLUDE_EXTRA_SOURCES | Config::UNINHERITED);
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    protected function make(string $className, mixed $argument = null): mixed
    {
        return Injector::inst()->create($className, $argument);
    }

    protected function resolveDependencyFieldType(bool|int|string $fieldType): Type
    {
        if (is_bool($fieldType)) {
            return new BooleanType();
        }

        if (is_numeric($fieldType)) {
            return new IntegerType();
        }

        $name = $fieldType;

        if (str_contains($fieldType, '%$')) {
            $name = $this->resolvePrefixNotation($fieldType);
        }

        if (!$this->reflectionProvider->hasClass($name)) {
            return new StringType();
        }

        $classReflection = $this->reflectionProvider->getClass($name);

        // Keep the original name if it is an interface
        if (!$classReflection->isInterface()) {
            $name = $this->resolveInjectedClassName($name);
        }

        return new FullyQualifiedObjectType($name);
    }

    /**
     * @param class-string $className
     * @param class-string<DBField> $fieldType
     */
    protected function resolveDBFieldType(string $className, string $fieldName, string $fieldType): Type
    {
        /** @var DBField $field */
        $field = $this->make($fieldType, 'Temp');
        $classReflection = $this->reflectionProvider->getClass($field::class);

        foreach (self::DBFIELD_TO_TYPE_MAPPING as $dbClass => $type) {
            if (!$this->reflectionProvider->hasClass($dbClass)) {
                continue;
            }

            if (!$classReflection->is($dbClass)) {
                continue;
            }

            return new $type();
        }

        // Instantiate the object so we can check for required fields
        $object = $this->make($className);

        // Fallback case
        if (!$object instanceof DataObject && !$object instanceof Extension) {
            return new StringType();
        }

        // If the object is an extension, create a mock DataObject and add the extension to it
        if ($object instanceof Extension) {
            $object = new class extends DataObject implements TestOnly {};
            $object::add_extension($className);
        }

        // Check if the field is required
        if ($object->getCMSCompositeValidator()->fieldIsRequired($fieldName)) {
            return new StringType();
        }

        // This is not required and therefore is nullable
        return new UnionType([new NullType(), new StringType()]);
    }

    /**
     * @param string[]|string $fieldType
     * @param array{through: class-string<DataObject>, from: string, to: string}|string $fieldType
     */
    protected function resolveRelationFieldType(array|string $fieldType): Type
    {
        $className = '';

        if (is_array($fieldType)) {
            $className = $fieldType['through'];
        }

        if (is_string($fieldType)) {
            $className = $this->resolveDotNotation($fieldType);
        }

        if ($this->reflectionProvider->hasClass($className)) {
            $className = $this->resolveInjectedClassName($className);
        }

        return new FullyQualifiedObjectType($className);
    }

    protected function resolveDotNotation(string $fieldType): string
    {
        [$class] = explode('.', $fieldType, 2);

        return $class;
    }

    protected function resolvePrefixNotation(string $fieldType): string
    {
        [$_, $class] = explode('%$', $fieldType, 2);

        return $class;
    }

    protected function resolveExtensionClassName(string $extensionName): ?string
    {
        $matches = [];

        if (preg_match(self::EXTENSION_CLASSNAME_REGEX, $extensionName, $matches) === false) {
            return null;
        }

        $resolved = $matches[1];

        if (!$this->reflectionProvider->hasClass($resolved)) {
            return null;
        }

        return $this->resolveInjectedClassName($resolved);
    }

    /**
     * Resolve the class name with the Injector, as it may have been replaced.
     *
     * @param class-string $className
     */
    protected function resolveInjectedClassName(string $className): string
    {
        try {
            return $this->make($className)::class;
        } catch (Exception) {
        }

        // Fallback case
        return $className;
    }
}
