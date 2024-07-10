<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeAnalyser;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use SilverStripe\Core\Extension;

final readonly class ClassAnalyser
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isExtension(Class_ $class): bool
    {
        $className = (string) $this->nodeNameResolver->getName($class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return $classReflection->isSubclassOf(Extension::class);
    }
}
