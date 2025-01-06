<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\AddHasOnePropertyAndMethodAnnotationsToDataObjectTest
 */
final class AddHasOnePropertyAndMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'Bar' => Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @method Bar Bar()
 * @property int $BarID
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_one = [
        'Bar' => Bar::class,
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

        $newDocTagValueNodes = [];
        $hasOneProperties = $this->typeResolver->resolveInjectedPropertyTypesFromConfigurationProperty(
            $classReflection,
            SilverstripeConstants::PROPERTY_HAS_ONE
        );

        $newDocTagValueNodes = [
            ...$newDocTagValueNodes,
            ...$this->phpDocHelper->convertTypesToPropertyTagValueNodes(
                $hasOneProperties
            ),
        ];

        $hasOneMethods = $this->typeResolver->resolveInjectedMethodTypesFromConfigurationProperty(
            $classReflection,
            SilverstripeConstants::PROPERTY_HAS_ONE
        );

        return [
            ...$newDocTagValueNodes,
            ...$this->phpDocHelper->convertTypesToMethodTagValueNodes(
                $hasOneMethods
            ),
        ];
    }
}
