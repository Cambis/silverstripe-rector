<?php

declare(strict_types=1);

namespace SilverstripeRector\Rector\Class_;

use PhpParser\Node\Stmt\Class_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use SilverStripe\Core\Extension;
use Webmozart\Assert\Assert;

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

    /**
     * @param string[] $configuration
     */
    final public function configure(array $configuration): void
    {
        $setTypeStyle = $configuration[self::SET_TYPE_STYLE] ?? self::SET_INTERSECTION;
        Assert::oneOf($setTypeStyle, [self::SET_INTERSECTION, self::SET_UNION]);

        /** @var self::SET_INTERSECTION|self::SET_UNION $setTypeStyle */
        $this->setTypeStyle = $setTypeStyle;
    }

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

        return !$classReflection->isSubclassOf(Extension::class);
    }

    final protected function isIntersection(): bool
    {
        return $this->setTypeStyle === self::SET_INTERSECTION;
    }
}
