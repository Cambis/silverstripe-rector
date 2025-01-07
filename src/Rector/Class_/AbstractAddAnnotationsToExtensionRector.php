<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Webmozart\Assert\Assert;
use function in_array;

abstract class AbstractAddAnnotationsToExtensionRector extends AbstractAddAnnotationsRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @deprecated since 1.0.0
     */
    final public const SET_TYPE_STYLE = 'set_type_style';

    /**
     * @api
     * @deprecated since 1.0.0
     */
    final public const SET_INTERSECTION = 'intersection';

    /**
     * @api
     * @deprecated since 1.0.0
     */
    final public const SET_UNION = 'union';

    /**
     * @var self::SET_INTERSECTION|self::SET_UNION
     * @deprecated since 1.0.0
     */
    protected string $setTypeStyle = self::SET_INTERSECTION;

    /**
     * @deprecated since 1.0.0
     */
    #[Override]
    final public function configure(array $configuration): void
    {
        $setTypeStyle = $configuration[self::SET_TYPE_STYLE] ?? self::SET_INTERSECTION;
        Assert::oneOf($setTypeStyle, [self::SET_INTERSECTION, self::SET_UNION]);

        /** @var self::SET_INTERSECTION|self::SET_UNION $setTypeStyle */
        $this->setTypeStyle = $setTypeStyle;
    }

    #[Override]
    final protected function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $className = $this->nodeNameResolver->getName($class);

        if ($className === null) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if (!$classReflection->isSubclassOf('SilverStripe\Core\Extension')) {
            return true;
        }

        $parentReflection = $classReflection->getParentClass();

        if (!$parentReflection instanceof ClassReflection) {
            return true;
        }

        // Only allow child of these classes, no subchilds allowed
        return !in_array($parentReflection->getName(), $this->getAllowedParents(), true);
    }

    /**
     * @deprecated since 1.0.0
     */
    final protected function isIntersection(): bool
    {
        return $this->setTypeStyle === self::SET_INTERSECTION;
    }

    /**
     * @return list<class-string>
     */
    final protected function getAllowedParents(): array
    {
        return ['SilverStripe\Core\Extension', 'SilverStripe\ORM\DataExtension'];
    }
}
