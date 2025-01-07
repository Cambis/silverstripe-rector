<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\TypeResolver\TypeResolver;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
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
use function str_contains;
use function str_starts_with;
use function substr;

final class DependencyInjectionPropertyTypeResolver implements PropertyTypeResolverInterface, TypeResolverAwareInterface, LazyTypeResolverInterface
{
    private TypeResolver $typeResolver;

    public function __construct(
        private readonly ClassReflectionAnalyser $classReflectionAnalyser,
        private readonly ConfigurationResolver $configurationResolver,
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
        if (str_contains($fieldType, '%$')) {
            $fieldType = $this->configurationResolver->resolvePrefixNotation($fieldType);
        }

        // Remove leading backslash
        if (str_starts_with($fieldType, '\\')) {
            $fieldType = substr($fieldType, 1);
        }

        if (str_contains($fieldType, '.')) {
            $fieldType = $this->configurationResolver->resolveDotNotation($fieldType);
        }

        if (!$this->reflectionProvider->hasClass($fieldType)) {
            return new StringType();
        }

        return new ObjectType($fieldType);
    }
}
