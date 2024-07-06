<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use SilverStripe\ORM\HasManyList;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\AddHasManyMethodAnnotationsToDataObjectRectorTest
 */
final class AddHasManyMethodAnnotationsToDataObjectRector extends AbstractAddAnnotationsToDataObjectRector
{
    #[Override]
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
    #[Override]
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $classConst = $classReflection->getName();

        $hasManyMethods = $this->configurationPropertyTypeResolver->resolveMethodTypesFromManyRelation(
            $classConst,
            SilverstripeConstants::PROPERTY_HAS_MANY,
            HasManyList::class
        );

        return $this->docBlockHelper->convertTypesToMethodTagValueNodes(
            $hasManyMethods
        );
    }
}
