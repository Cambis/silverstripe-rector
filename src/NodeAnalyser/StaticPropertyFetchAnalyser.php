<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeAnalyser;

use Cambis\Silverstan\ReflectionAnalyser\ClassReflectionAnalyser;
use Cambis\Silverstan\ReflectionAnalyser\PropertyReflectionAnalyser;
use PhpParser\Node\Expr\StaticPropertyFetch;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PHPStan\ScopeFetcher;

final readonly class StaticPropertyFetchAnalyser
{
    public function __construct(
        private ClassReflectionAnalyser $classReflectionAnalyser,
        private NodeNameResolver $nodeNameResolver,
        private PropertyReflectionAnalyser $propertyReflectionAnalyser
    ) {
    }

    public function isConfigurationProperty(StaticPropertyFetch $staticPropertyFetch): bool
    {
        $scope = ScopeFetcher::fetch($staticPropertyFetch);

        if (!$scope->isInClass()) {
            return false;
        }

        $classReflection = $scope->getClassReflection();

        if (!$this->classReflectionAnalyser->isConfigurable($classReflection)) {
            return false;
        }

        $propertyName = $this->nodeNameResolver->getName($staticPropertyFetch->name) ?? '';

        if ($propertyName === '') {
            return false;
        }

        if (!$classReflection->hasStaticProperty($propertyName)) {
            return false;
        }

        $propertyReflection = $classReflection->getStaticProperty($propertyName);

        return $this->propertyReflectionAnalyser->isConfigurationProperty($propertyReflection);
    }
}
