<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverStripe\ORM\ManyManyList;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\AddBelongsManyManyMethodAnnotationsToDataObjectRectorTest
 */
final class AddBelongsManyManyMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $belongs_many_many = [
        'Bars' => Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @method \SilverStripe\ORM\ManyManyList|Bar[] Bars()
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $belongs_many_many = [
        'Bars' => Bar::class,
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

        $belongsManyManyMethods = $this->silverstripeAnalyzer->extractMethodUnionTypesFromManyRelation(
            $classConst,
            SilverstripeConstants::PROPERTY_BELONGS_MANY_MANY,
            ManyManyList::class
        );

        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $belongsManyManyMethods
        );
    }
}
