<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Cambis\Silverstan\TypeResolver\TypeResolver;
use Cambis\SilverstripeRector\AnnotationUpdater\AnnotationUpdater;
use Cambis\SilverstripeRector\DataRecordResolver\DataRecordResolver;
use Cambis\SilverstripeRector\NodeAnalyser\ClassAnalyser;
use Cambis\SilverstripeRector\PhpDocHelper\PhpDocHelper;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\Exception\NotImplementedYetException;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

abstract class AbstractAddAnnotationsRector extends AbstractRector implements DocumentedRuleInterface, RelatedConfigInterface
{
    /**
     * @readonly
     */
    protected AnnotationUpdater $annotationUpdater;
    /**
     * @readonly
     */
    protected ClassAnalyser $classAnalyser;
    /**
     * @readonly
     */
    protected DataRecordResolver $dataRecordResolver;
    /**
     * @readonly
     */
    protected DocBlockUpdater $docBlockUpdater;
    /**
     * @readonly
     */
    protected PhpDocHelper $phpDocHelper;
    /**
     * @readonly
     */
    protected PhpDocInfoFactory $phpDocInfoFactory;
    /**
     * @readonly
     */
    protected ReflectionProvider $reflectionProvider;
    /**
     * @readonly
     */
    protected TypeResolver $typeResolver;
    /**
     * @readonly
     */
    protected StaticTypeMapper $staticTypeMapper;
    /**
     * @var array<class-string<PhpDocTagValueNode>, string>
     */
    private const TAGS_TYPES_TO_NAMES = [
        ExtendsTagValueNode::class => '@extends',
        MethodTagValueNode::class => '@method',
        MixinTagValueNode::class => '@mixin',
        ImplementsTagValueNode::class => '@implements',
        PropertyTagValueNode::class => '@property',
        TemplateTagValueNode::class => '@template',
    ];

    public function __construct(AnnotationUpdater $annotationUpdater, ClassAnalyser $classAnalyser, DataRecordResolver $dataRecordResolver, DocBlockUpdater $docBlockUpdater, PhpDocHelper $phpDocHelper, PhpDocInfoFactory $phpDocInfoFactory, ReflectionProvider $reflectionProvider, TypeResolver $typeResolver, StaticTypeMapper $staticTypeMapper)
    {
        $this->annotationUpdater = $annotationUpdater;
        $this->classAnalyser = $classAnalyser;
        $this->dataRecordResolver = $dataRecordResolver;
        $this->docBlockUpdater = $docBlockUpdater;
        $this->phpDocHelper = $phpDocHelper;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->reflectionProvider = $reflectionProvider;
        $this->typeResolver = $typeResolver;
        $this->staticTypeMapper = $staticTypeMapper;
    }

    /**
     * @return array<class-string<Class_>>
     */
    #[Override]
    final public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $newDocTagValueNodes = $this->getNewDocTagValueNodes($node);

        if ($newDocTagValueNodes === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $this->annotationUpdater->updateExistingAnnotations($node, $phpDocInfo, $newDocTagValueNodes);

        $newDocTagValueNodes = $this->annotationUpdater->filterOutExistingAnnotations($phpDocInfo, $newDocTagValueNodes, $node);

        if ($newDocTagValueNodes === []) {
            return null;
        }

        foreach ($newDocTagValueNodes as $newDocTagValueNode) {
            $tagName = $this->resolveNameForPhpDocTagValueNode($newDocTagValueNode);
            $phpDocTagNode = new PhpDocTagNode($tagName, $newDocTagValueNode);
            $phpDocInfo->addPhpDocTagNode($phpDocTagNode);
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * @throws NotImplementedYetException
     */
    protected function resolveNameForPhpDocTagValueNode(PhpDocTagValueNode $phpDocTagValueNode): string
    {
        foreach (self::TAGS_TYPES_TO_NAMES as $tagValueNodeType => $name) {
            /** @var class-string<PhpDocTagValueNode> $tagValueNodeType */
            if ($phpDocTagValueNode instanceof $tagValueNodeType) {
                return $name;
            }
        }

        throw new NotImplementedYetException(__METHOD__ . ' not yet implemented for ' . get_class($phpDocTagValueNode));
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    abstract protected function getNewDocTagValueNodes(Class_ $class): array;

    abstract protected function shouldSkipClass(Class_ $class): bool;
}
