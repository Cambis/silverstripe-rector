<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver\TypeResolver;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\ReflectionAnalyser\ClassReflectionAnalyser;
use Cambis\Silverstan\TypeFactory\TypeFactory;
use Cambis\Silverstan\TypeResolver\Contract\LazyTypeResolverInterface;
use Cambis\Silverstan\TypeResolver\Contract\PropertyTypeResolverInterface;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use function array_unique;
use function is_array;

/**
 * This resolver tracks extensible extensions and saves them in a meta property `__getOwns`.
 */
final class ExtensibleOwnsMetaPropertyTypeResolver implements PropertyTypeResolverInterface, LazyTypeResolverInterface
{
    /**
     * @readonly
     */
    private ClassReflectionAnalyser $classReflectionAnalyser;
    /**
     * @readonly
     */
    private ConfigurationResolver $configurationResolver;
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    private TypeFactory $typeFactory;
    public function __construct(ClassReflectionAnalyser $classReflectionAnalyser, ConfigurationResolver $configurationResolver, ReflectionProvider $reflectionProvider, TypeFactory $typeFactory)
    {
        $this->classReflectionAnalyser = $classReflectionAnalyser;
        $this->configurationResolver = $configurationResolver;
        $this->reflectionProvider = $reflectionProvider;
        $this->typeFactory = $typeFactory;
    }

    public function getConfigurationPropertyName(): string
    {
        return '__silverstan_owns';
    }

    /**
     * @phpstan-ignore-next-line return.unusedType
     * @return int|true
     */
    public function getExcludeMiddleware()
    {
        return ConfigurationResolver::EXCLUDE_INHERITED;
    }

    public function resolve(ClassReflection $classReflection): array
    {
        if (!$this->classReflectionAnalyser->isExtensible($classReflection)) {
            return [];
        }
        $extensions = $this->configurationResolver->get($classReflection->getName(), 'extensions', $this->getExcludeMiddleware());
        if (!is_array($extensions) || $extensions === []) {
            return [];
        }
        /** @var array<string|null> $extensions */
        $extensions = array_unique($extensions);
        $types = [];
        foreach ($extensions as $extension) {
            // Check for nullified extension name
            if ($extension === null) {
                continue;
            }

            $extensionClassName = $this->configurationResolver->resolveExtensionClassName($extension);

            if ($extensionClassName === null) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($extensionClassName);

            if (!$classReflection->isSubclassOf('SilverStripe\Core\Extension')) {
                continue;
            }

            $types[] = $this->typeFactory->createExtensibleTypeFromType(new ObjectType($extensionClassName));
        }
        if ($types === []) {
            return [];
        }
        return [
            '__getOwns' => TypeCombinator::union(...$types),
        ];
    }
}
