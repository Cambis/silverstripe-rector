<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe413\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\TypeCombinator;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\AddManyManyMethodAnnotationsToDataObjectRectorTest
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
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $manyManyMethods = $this->typeResolver->resolveInjectedMethodTypesFromConfigurationProperty(
            $classReflection,
            SilverstripeConstants::PROPERTY_MANY_MANY,
        );
        // List types from Silverstan are generic, transform them into the `DataList|Item[]` format
        foreach ($manyManyMethods as $name => $type) {
            if (!$type instanceof GenericObjectType) {
                continue;
            }

            $arrayType = new ArrayType(new IntegerType(), $type->getTypes()[0]);
            $manyManyMethods[$name] = TypeCombinator::union(new FullyQualifiedObjectType($type->getClassName()), $arrayType);
        }
        return $this->phpDocHelper->convertTypesToMethodTagValueNodes(
            $manyManyMethods
        );
    }
}
