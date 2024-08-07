<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\TypeResolver;

use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionGenericObjectType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerIntersectionType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerUnionType;
use Cambis\SilverstripeRector\TypeResolver\AbstractConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\ManyManyThroughList;
use SilverStripe\View\ViewableData;
use function array_filter;
use function array_key_exists;
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

        if ($relation === []) {
            return $properties;
        }

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

        $parentClassName = $classReflection->getParentClass() instanceof ClassReflection ? $classReflection->getParentClass()->getName() : Extension::class;

        if ($owners === []) {
            return new ExtensionGenericObjectType($parentClassName, [new StaticType($classReflection)]);
        }

        $owners = array_filter($owners, function (string $owner) use ($className): bool {
            return in_array(
                $className,
                $this->getConfig($owner, SilverstripeConstants::PROPERTY_EXTENSIONS) ?? [],
                true
            );
        });

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

        return new ExtensionGenericObjectType($parentClassName, [count($types) === 1 ? array_pop($types) : new ExtensionOwnerUnionType($types)]);
    }
}
