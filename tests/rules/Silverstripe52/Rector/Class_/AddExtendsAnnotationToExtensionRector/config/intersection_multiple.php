<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionGenericObjectType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerIntersectionType;
use Cambis\SilverstripeRector\StaticTypeMapper\ValueObject\Type\ExtensionOwnerUnionType;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockOne;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\Source\OwnerMockTwo;
use Cambis\SilverstripeRector\TypeResolver\AbstractConfigurationPropertyTypeResolver;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\Config\RectorConfig;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeSetList::WITH_SILVERSTRIPE_API,
        SilverstripeSetList::WITH_RECTOR_SERVICES,
    ]);

    $rectorConfig->rule(AddExtendsAnnotationToExtensionRector::class);

    // Mock the return of ConfigurationPropertyTypeResolverInterface to get some usable types to test
    $rectorConfig->singleton(ConfigurationPropertyTypeResolverInterface::class, static function (RectorConfig $config): ConfigurationPropertyTypeResolverInterface {
        /** @var ReflectionProvider $reflectionProvider */
        $reflectionProvider = $config->make(ReflectionProvider::class);

        return new class($reflectionProvider) extends AbstractConfigurationPropertyTypeResolver {
            public function resolveMethodTypesFromManyRelation(string $className, string $relationName, string $listName = DataList::class): array
            {
                return [];
            }

            public function resolveOwnerTypeFromOwners(string $className, bool $isIntersection): Type
            {
                $classReflection = $this->reflectionProvider->getClass($className);
                $parentClassName = $classReflection->getParentClass() instanceof ClassReflection ? $classReflection->getParentClass()->getName() : Extension::class;

                $genericTypes = [new ExtensionOwnerUnionType([
                    new ExtensionOwnerIntersectionType([new FullyQualifiedObjectType(OwnerMockOne::class), new StaticType($classReflection)]),
                    new ExtensionOwnerIntersectionType([new FullyQualifiedObjectType(OwnerMockTwo::class), new StaticType($classReflection)]),
                ])];

                return new ExtensionGenericObjectType($parentClassName, $genericTypes);
            }
        };
    });
};
