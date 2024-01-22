<?php

declare(strict_types=1);

namespace SilverstripeRector\Silverstripe52\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function count;

/**
 * @see \SilverstripeRector\Tests\Silverstripe52\Rector\Class_\DataListMethodAnnotationToGenericDataListMethodAnnotationRector\DataListMethodAnnotationToGenericDataListMethodAnnotationRectorTest
 */
final class DataListMethodAnnotationToGenericDataListMethodAnnotationRector extends AbstractRector
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeComparator $typeComparator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Update DataList annotations to use generics.', [new CodeSample(
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
            ,
            <<<'CODE_SAMPLE'
/**
 * @method \SilverStripe\ORM\HasManyList<Bar> Bars()
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
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $hasChanged = false;
        $dataListType = new ObjectType(DataList::class);
        $dataObjectType = new ObjectType(DataObject::class);
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $phpDocNode = $phpDocInfo->getPhpDocNode();

        foreach ($phpDocNode->children as $key => $phpDocTagNode) {
            if (!$phpDocTagNode instanceof PhpDocTagNode) {
                continue;
            }

            if ($phpDocTagNode->name !== '@method') {
                continue;
            }

            $phpDocTagValueNode = $phpDocTagNode->value;

            if (!$phpDocTagValueNode instanceof PhpDocTagValueNode) {
                continue;
            }

            if (!$phpDocTagValueNode instanceof MethodTagValueNode) {
                continue;
            }

            $returnType = $phpDocTagValueNode->returnType;

            if (!$returnType instanceof TypeNode) {
                continue;
            }

            // If it is already generic we can skip
            if ($returnType instanceof GenericTypeNode) {
                continue;
            }

            $type = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($returnType, $node);

            // `@method DataList Foo()`
            if ($type instanceof ObjectType) {
                if (!$this->typeComparator->isSubtype($type, $dataListType)) {
                    continue;
                }

                unset($phpDocNode->children[$key]);

                $phpDocInfo->addTagValueNode(new MethodTagValueNode(
                    false,
                    new GenericTypeNode(
                        new FullyQualifiedIdentifierTypeNode($type->getClassName()),
                        [new FullyQualifiedIdentifierTypeNode(DataObject::class)]
                    ),
                    $phpDocTagValueNode->methodName,
                    [],
                    '',
                    []
                ));

                $hasChanged = true;
            }

            // `@method DataList|Foo[] Foo()`
            if ($type instanceof UnionType) {
                if (count($type->getTypes()) !== 2) {
                    continue;
                }

                $listType = $type->getTypes()[0];

                if (!$listType instanceof ObjectType) {
                    continue;
                }

                if (!$this->typeComparator->isSubtype($listType, $dataListType)) {
                    continue;
                }

                $itemType = $type->getTypes()[1];

                if ($itemType instanceof ArrayType) {
                    $itemType = $itemType->getItemType();
                }

                if (!$itemType instanceof ObjectType) {
                    continue;
                }

                if (!$this->typeComparator->isSubtype($itemType, $dataObjectType)) {
                    continue;
                }

                unset($phpDocNode->children[$key]);

                $phpDocInfo->addTagValueNode(new MethodTagValueNode(
                    false,
                    new GenericTypeNode(
                        new FullyQualifiedIdentifierTypeNode($listType->getClassName()),
                        [new FullyQualifiedIdentifierTypeNode($itemType->getClassName())]
                    ),
                    $phpDocTagValueNode->methodName,
                    [],
                    '',
                    []
                ));

                $hasChanged = true;
            }
        }

        if (!$hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    private function shouldSkipClass(Class_ $class): bool
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

        if ($classReflection->isSubclassOf(Extension::class)) {
            return false;
        }

        return !$classReflection->isSubclassOf(DataObject::class);
    }
}
