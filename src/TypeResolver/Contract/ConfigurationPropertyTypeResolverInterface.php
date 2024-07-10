<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver\Contract;

use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBBoolean;
use SilverStripe\ORM\FieldType\DBDecimal;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBFloat;
use SilverStripe\ORM\FieldType\DBInt;

/**
 * A service that is used to resolve the `\PHPStan\Type\Type` of a Silverstripe configuration property.
 */
interface ConfigurationPropertyTypeResolverInterface
{
    /**
     * @var array<class-string<DBField>, class-string<Type>>
     */
    final public const DBFIELD_TO_TYPE_MAPPING = [
        DBBoolean::class => BooleanType::class,
        DBDecimal::class => FloatType::class,
        DBFloat::class => FloatType::class,
        DBInt::class => IntegerType::class,
    ];

    /**
     * Resolve property types from the `$db` configuration property.
     *
     * @param class-string $className
     * @return Type[]
     */
    public function resolvePropertyTypesFromDBFields(string $className): array;

    /**
     * Resolve property types from the `$belongs_to` or `$has_one` configuration properties.
     *
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_TO|SilverstripeConstants::PROPERTY_HAS_ONE $relationName
     * @return Type[]
     */
    public function resolvePropertyTypesFromSingleRelation(string $className, string $relationName): array;

    /**
     * Resolve method types from the `$belongs_to` or `$has_one` configuration properties.
     *
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_TO|SilverstripeConstants::PROPERTY_HAS_ONE $relationName
     * @return Type[]
     */
    public function resolveMethodTypesFromSingleRelation(string $className, string $relationName): array;

    /**
     * Resolve method types from the `$belongs_many_many`, `$has_many` or `$many_many` configuration properties.
     *
     * @param class-string $className
     * @param SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY|SilverstripeConstants::PROPERTY_HAS_MANY|SilverstripeConstants::PROPERTY_MANY_MANY $relationName
     * @param class-string<DataList<DataObject>> $listName
     * @return Type[]
     */
    public function resolveMethodTypesFromManyRelation(string $className, string $relationName, string $listName = DataList::class): array;

    /**
     * Resolve property types from the `$dependencies` configuration property.
     *
     * @param class-string $className
     * @return Type[]
     */
    public function resolvePropertyTypesFromDependencies(string $className): array;

    /**
     * Resolve mixin types from the `$extensions` configuration property.
     *
     * @param class-string $className
     * @return Type[]
     */
    public function resolveMixinTypesFromExtensions(string $className): array;

    /**
     * Resolve type from the owners of an extension.
     *
     * @param class-string $className extension name
     * @param bool $isIntersection if true the type will will be returned in the `Extensible&Extension` (intersection) format.
     * If false the type will be returned in the `Extensible|Extension` (union) format.
     */
    public function resolveOwnerTypeFromOwners(string $className, bool $isIntersection): Type;
}
