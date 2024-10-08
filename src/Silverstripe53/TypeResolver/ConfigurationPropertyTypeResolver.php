<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe53\TypeResolver;

use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionGenericObjectType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerIntersectionType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerUnionType;
use Cambis\SilverstripeRector\TypeResolver\AbstractConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\ManyManyThroughList;
use SilverStripe\View\ViewableData;
use function array_filter;
use function array_key_exists;
use function array_map;
use function array_pop;
use function count;
use function in_array;
use function is_array;

final class ConfigurationPropertyTypeResolver extends AbstractConfigurationPropertyTypeResolver
{
    /**
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY|SilverstripeConstants::PROPERTY_HAS_MANY|SilverstripeConstants::PROPERTY_MANY_MANY $relationName
     * @param class-string<DataList<DataObject>> $listName
     * @return Type[]
     */
    #[Override]
    public function resolveMethodTypesFromManyRelation(
        string $className,
        string $relationName,
        string $listName = DataList::class
    ): array {
        $properties = [];

        $relation = $this->getConfig($className, $relationName) ?? [];

        if (!is_array($relation) || $relation === []) {
            return $properties;
        }

        /** @var array<string|string[]> $relation */
        foreach ($relation as $fieldName => $fieldType) {
            $relationFieldType = $this->resolveRelationFieldType($fieldType);

            if (!$relationFieldType instanceof FullyQualifiedObjectType) {
                continue;
            }

            if (
                is_array($fieldType) &&
                array_key_exists('through', $fieldType) && $listName === ManyManyList::class
            ) {
                $listName = ManyManyThroughList::class;
            }

            $properties[$fieldName] = new GenericObjectType(
                $listName,
                [$relationFieldType],
            );
        }

        return $properties;
    }

    /**
     * @param class-string $className extension name
     */
    #[Override]
    public function resolveOwnerTypeFromOwners(string $className, bool $isIntersection): Type
    {
        /** @var array<class-string> $owners */
        $owners = array_filter(ClassInfo::allClasses(), static function (string $owner) use ($className): bool {
            return ViewableData::has_extension($owner, $className, true);
        });

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($owners === []) {
            return new ExtensionGenericObjectType('SilverStripe\Core\Extension', [new StaticType($classReflection)]);
        }

        $owners = array_filter($owners, function (string $owner) use ($className): bool {
            /** @var class-string[] $extensions */
            $extensions = $this->getConfig($owner, SilverstripeConstants::PROPERTY_EXTENSIONS) ?? [];

            // Use the Injector to resolve the extension class name as it may have been replaced
            return in_array(
                $className,
                array_map(function (string $extensionName): string {
                    return $this->resolveInjectedClassName($extensionName);
                }, $extensions),
                true
            );
        });

        if ($owners === []) {
            return new ExtensionGenericObjectType('SilverStripe\Core\Extension', [new StaticType($classReflection)]);
        }

        $types = [];

        foreach ($owners as $owner) {
            if ($isIntersection) {
                $types[] = new ExtensionOwnerIntersectionType([new FullyQualifiedObjectType($owner), new StaticType($classReflection)]);
            } else {
                $types[] = new FullyQualifiedObjectType($owner);
            }
        }

        if (!$isIntersection) {
            $types[] = new StaticType($classReflection);
        }

        return new ExtensionGenericObjectType('SilverStripe\Core\Extension', [count($types) === 1 ? array_pop($types) : new ExtensionOwnerUnionType($types)]);
    }
}
