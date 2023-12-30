<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverStripe\ORM\ManyManyList;
use SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use SilverstripeRector\ValueObject\SilverstripeConstants;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\AddManyManyMethodAnnotationsToDataObjectRectorTest
 */
final class AddManyManyMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $many_many = [
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
    private static array $many_many = [
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
    protected function getNewDocTagValueNodes(Node $node): array
    {
        $className = (string) $this->nodeNameResolver->getName($node);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();


        $manyManyMethods = $this->configurableAnalyzer->extractMethodTypesFromManyRelation(
            $classConst,
            SilverstripeConstants::MANY_MANY,
            ManyManyList::class
        );

        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $manyManyMethods
        );
    }
}
