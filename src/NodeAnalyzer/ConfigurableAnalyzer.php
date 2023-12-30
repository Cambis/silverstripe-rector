<?php

declare(strict_types=1);

namespace SilverstripeRector\NodeAnalyzer;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\ManyManyThroughList;
use SilverstripeRector\ValueObject\SilverstripeConstants;

use function array_filter;
use function array_key_exists;
use function array_unique;
use function explode;
use function in_array;
use function is_array;
use function is_bool;
use function is_null;
use function is_numeric;
use function is_string;
use function str_contains;

final class ConfigurableAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    public function extractPropertyTypesFromDBFields(string $className): array
    {
        $properties = [];
        $db = $this->getConfig($className, SilverstripeConstants::DB);

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
     * @return Type[]
     */
    public function extractPropertyTypesFromSingleRelation(string $className, string $relationName): array
    {
        $properties = [];
        $relation = $this->getConfig($className, $relationName);

        if (is_null($relation)) {
            return $properties;
        }

        foreach ($relation as $fieldName => $_) {
            $properties['$' . $fieldName . 'ID'] = new IntegerType();
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    public function extractMethodTypesFromSingleRelation(string $className, string $relationName): array
    {
        $properties = [];
        $relation = $this->getConfig($className, $relationName);

        if (is_null($relation)) {
            return $properties;
        }

        foreach ($relation as $fieldName => $fieldType) {
            $properties[$fieldName] = $this->resolveRelationFieldType($fieldType);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @param class-string<DataList> $listName
     * @return Type[]
     */
    public function extractMethodTypesFromManyRelation(
        string $className,
        string $relationName,
        string $listName = DataList::class
    ): array {
        $properties = [];
        $relation = $this->getConfig($className, $relationName) ?? [];

        if ($relation === []) {
            return $properties;
        }

        foreach ($relation as $fieldName => $fieldType) {
            $relationFieldType = $this->resolveRelationFieldType($fieldType);

            if (
                is_array($fieldType) &&
                array_key_exists('through', $fieldType) && $listName === ManyManyList::class
            ) {
                $listName = ManyManyThroughList::class;
            }

            $properties[$fieldName] = new UnionType([
                new FullyQualifiedObjectType($listName),
                new ArrayType(new IntegerType(), $relationFieldType),
            ]);
        }

        return $properties;
    }

    /**
     * @param class-string $className
     * @return Type[]
     */
    public function extractPropertyTypesFromDependencies($className): array
    {
        $properties = [];

        /** @var string[] */
        $dependencies = $this->getConfig($className, SilverstripeConstants::DEPENDENCIES);

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
    public function extractMixinTypesFromExtensions($className): array
    {
        $properties = [];
        $extensions = $this->getConfig($className, SilverstripeConstants::EXTENSIONS) ?? [];

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
     * @param class-string $className extension name
     * @return Type[]
     */
    public function extractMethodTypesFromOwners($className): array
    {
        /** @var array<class-string> $owners */
        $owners = ClassInfo::classesWithExtension($className);
        $classReflection = $this->reflectionProvider->getClass($className);
        $thisType = new ThisType($classReflection);

        if ($owners === []) {
            return [
                SilverstripeConstants::GET_OWNER => $thisType,
            ];
        }

        $owners = array_filter($owners, function (string $owner) use ($className): bool {
            return in_array(
                $className,
                $this->getConfig($owner, SilverstripeConstants::EXTENSIONS) ?? [],
                true
            );
        });

        $types = [];

        foreach ($owners as $owner) {
            $types[] = new FullyQualifiedObjectType($owner);
        }

        $types[] = $thisType;

        return [
            SilverstripeConstants::GET_OWNER => new UnionType($types),
        ];
    }

    /**
     * @param class-string $className extension name
     * @return TypeNode[]
     */
    public function extractExtendsTypeNodesFromOwners($className): array
    {
        /** @var array<class-string> $owners */
        $owners = ClassInfo::classesWithExtension($className);

        if ($owners === []) {
            return [new IdentifierTypeNode('static')];
        }

        $owners = array_filter($owners, function (string $owner) use ($className): bool {
            return in_array(
                $className,
                $this->getConfig($owner, SilverstripeConstants::EXTENSIONS) ?? [],
                true
            );
        });

        $types = [];

        foreach ($owners as $owner) {
            $types[] = new FullyQualifiedIdentifierTypeNode($owner);
        }

        $types[] = new IdentifierTypeNode('static');

        return $types;
    }

    /**
     * @param class-string $className
     * @return mixed
     */
    private function getConfig(string $className, string $name)
    {
        return Config::inst()->get($className, $name, Config::EXCLUDE_EXTRA_SOURCES | Config::UNINHERITED);
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    private function make(string $className, mixed $argument = null)
    {
        return Injector::inst()->create($className, $argument);
    }

    private function resolveDependencyFieldType(bool|int|string $fieldType): Type
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
    private function resolveDBFieldType(string $fieldType): Type
    {
        /** @var DBField $field */
        $field = $this->make($fieldType, 'Temp');
        $classReflection = $this->reflectionProvider->getClass($field::class);

        foreach (SilverstripeConstants::DBFIELD_TO_TYPE_MAPPING as $dbClass => $type) {
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
     * @phpstan-param array{through: class-string<DataObject>, from: string, to: string}|string $fieldType
     */
    private function resolveRelationFieldType(array|string $fieldType): Type
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

    private function resolveDotNotation(string $fieldType): string
    {
        [$class] = explode('.', $fieldType, 2);

        return $class;
    }

    private function resolvePrefixNotation(string $fieldType): string
    {
        [$_, $class] = explode('%$', $fieldType, 2);

        return $class;
    }
}
