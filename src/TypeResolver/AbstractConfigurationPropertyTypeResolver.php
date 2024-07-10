<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver;

use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use function array_unique;
use function explode;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function str_contains;

/**
 * This class contains common abstractions which can be used from Silverstripe 4.13 and up.
 */
abstract class AbstractConfigurationPropertyTypeResolver implements ConfigurationPropertyTypeResolverInterface
{
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
            $properties['$' . $fieldName] = $this->resolveDBFieldType($fieldType);
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
            $classReflection = $this->reflectionProvider->getClass($extension);

            if (!$classReflection->isSubclassOf(Extension::class)) {
                continue;
            }

            $properties[] = new FullyQualifiedObjectType($extension);
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

        if ($this->reflectionProvider->hasClass($name)) {
            return new FullyQualifiedObjectType($name);
        }

        return new StringType();
    }

    /**
     * @param class-string<DBField> $fieldType
     */
    protected function resolveDBFieldType(string $fieldType): Type
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

        return new StringType();
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
}
