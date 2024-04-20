<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataExtension;
use Webmozart\Assert\Assert;
use function in_array;

abstract class AbstractAddAnnotationsToExtensionRector extends AbstractAddAnnotationsRector implements ConfigurableRectorInterface
{
    /**
     * @api
     */
    final public const SET_TYPE_STYLE = 'set_type_style';

    /**
     * @api
     */
    final public const SET_INTERSECTION = 'intersection';

    /**
     * @api
     */
    final public const SET_UNION = 'union';

    /**
     * @var self::SET_INTERSECTION|self::SET_UNION
     */
    protected string $setTypeStyle = self::SET_INTERSECTION;

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

        if (!$classReflection->isSubclassOf(Extension::class)) {
            return true;
        }

        $parentReflection = $classReflection->getParentClass();

        if (!$parentReflection instanceof ClassReflection) {
            return true;
        }

        // Only allow child of these classes, no subchilds allowed
        return !in_array($parentReflection->getName(), $this->getAllowedParents(), true);
    }

    final protected function isIntersection(): bool
    {
        return $this->setTypeStyle === self::SET_INTERSECTION;
    }

    /**
     * @return array<class-string<Extension>>
     */
    final protected function getAllowedParents(): array
    {
        return [Extension::class, DataExtension::class];
    }
}
