<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\Silverstan\Normaliser\Normaliser;
use Cambis\Silverstan\ReflectionAnalyser\ClassReflectionAnalyser;
use Cambis\Silverstan\TypeResolver\Contract\LazyTypeResolverInterface;
use Cambis\Silverstan\TypeResolver\Contract\PropertyTypeResolverInterface;
use Cambis\Silverstan\TypeResolver\Contract\TypeResolverAwareInterface;
use Cambis\Silverstan\TypeResolver\TypeResolver;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use function is_array;
use function is_string;

final class DependencyInjectionPropertyTypeResolver implements PropertyTypeResolverInterface, TypeResolverAwareInterface, LazyTypeResolverInterface
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
    private Normaliser $normaliser;
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    private TypeResolver $typeResolver;

    public function __construct(ClassReflectionAnalyser $classReflectionAnalyser, ConfigurationResolver $configurationResolver, Normaliser $normaliser, ReflectionProvider $reflectionProvider)
    {
        $this->classReflectionAnalyser = $classReflectionAnalyser;
        $this->configurationResolver = $configurationResolver;
        $this->normaliser = $normaliser;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getConfigurationPropertyName(): string
    {
        return 'dependencies';
    }

    /**
     * @phpstan-ignore return.unusedType
     * @return int|true
     */
    public function getExcludeMiddleware()
    {
        return ConfigurationResolver::EXCLUDE_INHERITED | ConfigurationResolver::EXCLUDE_EXTRA_SOURCES;
    }

    public function resolve(ClassReflection $classReflection): array
    {
        if (!$this->classReflectionAnalyser->isInjectable($classReflection)) {
            return [];
        }
        $types = [];
        $dependencies = $this->configurationResolver->get($classReflection->getName(), $this->getConfigurationPropertyName(), $this->getExcludeMiddleware());
        if (!is_array($dependencies) || $dependencies === []) {
            return $types;
        }
        /** @var array<array<mixed>|bool|int|string> $dependencies */
        foreach ($dependencies as $fieldName => $fieldType) {
            if (is_string($fieldType)) {
                $type = $this->resolveDependencyObjectType($fieldType);
            } else {
                $type = $this->typeResolver->resolveDependencyFieldType($fieldType);
            }

            $types[$fieldName] = $type;
        }
        return $types;
    }

    /**
     * @return static
     */
    public function setTypeResolver(TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
        return $this;
    }

    /**
     * Silverstan will resolve the classname if it has been replaced, we just want to leave it as is.
     */
    private function resolveDependencyObjectType(string $fieldType): Type
    {
        // Remove the prefix
        $name = $this->normaliser->normalisePrefixNotation($fieldType);

        // Remove leading backslash
        $name = $this->normaliser->normaliseNamespace($name);

        // Remove dot notation
        $name = $this->normaliser->normaliseDotNotation($name);

        if (!$this->reflectionProvider->hasClass($name)) {
            return new StringType();
        }

        return new ObjectType($name);
    }
}
