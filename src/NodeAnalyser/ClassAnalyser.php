<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\NodeAnalyser;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;

final class ClassAnalyser
{
    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;
    public function __construct(NodeNameResolver $nodeNameResolver, ReflectionProvider $reflectionProvider)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function isExtension(Class_ $class): bool
    {
        $className = (string) $this->nodeNameResolver->getName($class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return $classReflection->isSubclassOf('SilverStripe\Core\Extension');
    }
}
