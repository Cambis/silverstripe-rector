<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver;

use Cambis\Silverstan\ClassManifest\ClassManifest;
use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\TypeFactory\TypeFactory;
use Cambis\Silverstan\TypeResolver\Contract\LazyTypeResolverInterface;
use Cambis\Silverstan\TypeResolver\Contract\PropertyTypeResolverInterface;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\TypeCombinator;
use function array_filter;
use function array_map;
use function array_values;
use function in_array;

/**
 * This resolver tracks extension owners and saves them in a meta property `__getOwners`.
 *
 * Only returns `SilverStripe\Core\Extension` - no subclasses. Renaming `SilverStripe\Core\Extension` subclasses while adding the annotation can cause an inifinite loop.
 */
final class ExtensionOwnerMetaPropertyTypeResolver implements PropertyTypeResolverInterface, LazyTypeResolverInterface
{
    /**
     * @readonly
     */
    private ConfigurationResolver $configurationResolver;
    /**
     * @readonly
     */
    private ClassManifest $classManifest;
    /**
     * @readonly
     */
    private TypeFactory $typeFactory;
    public function __construct(ConfigurationResolver $configurationResolver, ClassManifest $classManifest, TypeFactory $typeFactory)
    {
        $this->configurationResolver = $configurationResolver;
        $this->classManifest = $classManifest;
        $this->typeFactory = $typeFactory;
    }

    public function getConfigurationPropertyName(): string
    {
        return '__silverstan_owners';
    }

    /**
     * @phpstan-ignore-next-line return.unusedType
     * @return int|true
     */
    public function getExcludeMiddleware()
    {
        return ConfigurationResolver::EXCLUDE_INHERITED | ConfigurationResolver::EXCLUDE_EXTRA_SOURCES;
    }

    public function resolve(ClassReflection $classReflection): array
    {
        if (!$classReflection->is('SilverStripe\Core\Extension')) {
            return [];
        }
        // Loop over class manifest and find owners of this extension
        $owners = array_filter(array_values($this->classManifest->getClasses()), function (string $owner) use ($classReflection): bool {
            /** @var array<class-string|null> $extensions */
            $extensions = $this->configurationResolver->get($owner, 'extensions', $this->getExcludeMiddleware()) ?? [];

            // Clean nullified named extensions
            $extensions = array_filter(array_values($extensions), static function (?string $value): bool {
                return $value !== null;
            });

            // Use the Injector to resolve the extension class name as it may have been replaced
            return in_array(
                $classReflection->getName(),
                array_map(function (string $extensionName): string {
                    return $this->configurationResolver->resolveClassName($extensionName);
                }, $extensions),
                true
            );
        });
        // Type to represent the extension itself
        $staticType = $this->typeFactory->createExtensibleTypeFromType(new StaticType($classReflection));
        // No owners
        if ($owners === []) {
            return [
                '__getOwners' => new GenericObjectType('SilverStripe\Core\Extension', [$staticType]),
            ];
        }
        $types = [];
        foreach ($owners as $owner) {
            $types[] = TypeCombinator::intersect(
                $this->typeFactory->createExtensibleTypeFromType(new ObjectType($owner)),
                $staticType
            );
        }
        return [
            '__getOwners' => new GenericObjectType('SilverStripe\Core\Extension', [TypeCombinator::union(...$types)]),
        ];
    }
}
