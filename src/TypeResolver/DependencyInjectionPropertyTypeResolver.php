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
use Override;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use function is_array;
use function is_string;

final class DependencyInjectionPropertyTypeResolver implements PropertyTypeResolverInterface, TypeResolverAwareInterface, LazyTypeResolverInterface
{
    private TypeResolver $typeResolver;

    public function __construct(
        private readonly ClassReflectionAnalyser $classReflectionAnalyser,
        private readonly ConfigurationResolver $configurationResolver,
        private readonly Normaliser $normaliser,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    #[Override]
    public function getConfigurationPropertyName(): string
    {
        return 'dependencies';
    }

    /**
     * @phpstan-ignore return.unusedType
     */
    #[Override]
    public function getExcludeMiddleware(): true|int
    {
        return ConfigurationResolver::EXCLUDE_INHERITED | ConfigurationResolver::EXCLUDE_EXTRA_SOURCES;
    }

    #[Override]
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

        /** @var array<non-empty-string, array<mixed>|bool|int|string> $dependencies */
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

    #[Override]
    public function setTypeResolver(TypeResolver $typeResolver): static
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

        // Check if the classname exists, if not resolve it
        if (!$this->reflectionProvider->hasClass($name)) {
            $name = $this->configurationResolver->resolveClassName($name);
        }

        // Fallback to string
        if (!$this->reflectionProvider->hasClass($name)) {
            return new StringType();
        }

        return new ObjectType($name);
    }
}
