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
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRectorTest
 */
final class AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $belongs_to = [
        'Bar' => Bar::class . '.Parent',
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
    private static array $belongs_to = [
        'Bar' => Bar::class . '.Parent',
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
        $classConst = $classReflection->getName();

        $newDocTagValueNodes = [];
        $belongsToProperties = $this->configurationPropertyTypeResolver->resolvePropertyTypesFromSingleRelation(
            $classConst,
            SilverstripeConstants::PROPERTY_BELONGS_TO
        );

        $newDocTagValueNodes = [
            ...$newDocTagValueNodes,
            ...$this->phpDocHelper->convertTypesToPropertyTagValueNodes(
                $belongsToProperties
            ),
        ];

        $belongsToMethods = $this->configurationPropertyTypeResolver->resolveMethodTypesFromSingleRelation(
            $classConst,
            SilverstripeConstants::PROPERTY_BELONGS_TO
        );

        return [
            ...$newDocTagValueNodes,
            ...$this->phpDocHelper->convertTypesToMethodTagValueNodes(
                $belongsToMethods
            ),
        ];
    }
}
