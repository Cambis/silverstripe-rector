<?php

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsRector;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\UnionType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_key_exists;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector\AddExtensionMixinAnnotationsToExtensibleRectorTest
 */
final class AddExtensionMixinAnnotationsToExtensibleRector extends AbstractAddAnnotationsRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $extensions = [
        Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @mixin Bar
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $extensions = [
        Bar::class,
    ];
}
CODE_SAMPLE
        ),
        ]);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    #[Override]
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $types = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty($classReflection, '__silverstan_owns');

        if ($types === []) {
            return [];
        }

        if (!array_key_exists('__getOwns', $types)) {
            return [];
        }

        $type = $types['__getOwns'];

        if ($type instanceof UnionType) {
            $types = $type->getTypes();
        }

        return [
            ...$this->phpDocHelper->convertTypesToMixinTagValueNodes(
                $types,
            ),
        ];
    }

    #[Override]
    protected function shouldSkipClass(Class_ $class): bool
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

        return !$classReflection->hasTraitUse('SilverStripe\Core\Extensible');
    }
}
