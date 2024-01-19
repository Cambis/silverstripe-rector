<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use SilverstripeRector\ValueObject\SilverstripeConstants;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRectorTest
 */
final class AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
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
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();

        $newDocTagValueNodes = [];
        $belongsToProperties = $this->configurableAnalyzer->extractPropertyTypesFromSingleRelation(
            $classConst,
            SilverstripeConstants::BELONGS_TO
        );

        $newDocTagValueNodes = [
            ...$newDocTagValueNodes,
            ...$this->docBlockHelper->convertTypesToPropertyTagValueNodes(
                $belongsToProperties
            ),
        ];

        $belongsToMethods = $this->configurableAnalyzer->extractMethodTypesFromSingleRelation(
            $classConst,
            SilverstripeConstants::BELONGS_TO
        );

        return [
            ...$newDocTagValueNodes,
            ...$this->docBlockHelper->convertTypesToMethodTagValueNodes(
                $belongsToMethods
            ),
        ];
    }
}
