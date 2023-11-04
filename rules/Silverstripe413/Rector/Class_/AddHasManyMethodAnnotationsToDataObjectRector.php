<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe413\Rector\Class_;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverStripe\ORM\HasManyList;
use SilverstripeRector\ValueObject\SilverstripeConstants;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\AddHasManyMethodAnnotationsToDataObjectRectorTest
 */
final class AddHasManyMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_many = [
        'Bars' => Bar::class,
    ];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @method \SilverStripe\ORM\HasManyList|Bar[] Bars()
 */
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $has_many = [
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


        $hasManyMethods = $this->configurableAnalyzer->extractMethodTypesFromManyRelation(
            $classConst,
            SilverstripeConstants::HAS_MANY,
            HasManyList::class
        );

        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $hasManyMethods
        );
    }
}
