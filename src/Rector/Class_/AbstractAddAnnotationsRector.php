<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Rector\Class_;

use Cambis\SilverstripeRector\NodeResolver\DataRecordResolver;
use Cambis\SilverstripeRector\PhpDoc\AnnotationUpdater;
use Cambis\SilverstripeRector\PhpDoc\PhpDocHelper;
use Cambis\SilverstripeRector\Rector\AbstractAPIAwareRector;
use Cambis\SilverstripeRector\TypeResolver\Contract\ConfigurationPropertyTypeResolverInterface;
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
use Rector\Exception\NotImplementedYetException;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\StaticTypeMapper\StaticTypeMapper;

abstract class AbstractAddAnnotationsRector extends AbstractAPIAwareRector
{
    /**
     * @readonly
     */
    protected AnnotationUpdater $annotationUpdater;
    /**
     * @readonly
     */
    protected ClassAnalyzer $classAnalyzer;
    /**
     * @readonly
     */
    protected ConfigurationPropertyTypeResolverInterface $configurationPropertyTypeResolver;
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

    public function __construct(AnnotationUpdater $annotationUpdater, ClassAnalyzer $classAnalyzer, ConfigurationPropertyTypeResolverInterface $configurationPropertyTypeResolver, DataRecordResolver $dataRecordResolver, DocBlockUpdater $docBlockUpdater, PhpDocHelper $phpDocHelper, PhpDocInfoFactory $phpDocInfoFactory, ReflectionProvider $reflectionProvider, StaticTypeMapper $staticTypeMapper)
    {
        $this->annotationUpdater = $annotationUpdater;
        $this->classAnalyzer = $classAnalyzer;
        $this->configurationPropertyTypeResolver = $configurationPropertyTypeResolver;
        $this->dataRecordResolver = $dataRecordResolver;
        $this->docBlockUpdater = $docBlockUpdater;
        $this->phpDocHelper = $phpDocHelper;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->reflectionProvider = $reflectionProvider;
        $this->staticTypeMapper = $staticTypeMapper;
    }

    /**
     * @return array<class-string<Class_>>
     */
    final public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorAPIAwareNode(Node $node): ?Node
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
