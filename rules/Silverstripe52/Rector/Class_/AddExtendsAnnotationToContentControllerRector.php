<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe52\Rector\Class_;

use Cambis\SilverstripeRector\Rector\Class_\AbstractAddAnnotationsRector;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Reflection\ClassReflection;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\SpacingAwareTemplateTagValueNode;
use Rector\Exception\ShouldNotHappenException;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector\AddExtendsAnnotationToContentControllerRectorTest
 */
final class AddExtendsAnnotationToContentControllerRector extends AbstractAddAnnotationsRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing dynamic annotations.', [new CodeSample(
            <<<'CODE_SAMPLE'
class Page extends \SilverStripe\ORM\Model\SiteTree
{
}

class PageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

class Homepage extends Page
{
}

class HomepageController extends PageController
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class Page extends \SilverStripe\ORM\Model\SiteTree
{
}

/**
 * @template T of Page
 * @extends \SilverStripe\CMS\Controllers\ContentController<T>
 */
class PageController extends \SilverStripe\CMS\Controllers\ContentController
{
}

class Homepage extends Page
{
}

/**
 * @extends PageController<Homepage>
 */
class HomepageController extends PageController
{
}
CODE_SAMPLE
            ,
        )]);
    }

    /**
     * @return PhpDocTagValueNode[]
     */
    protected function getNewDocTagValueNodes(Class_ $class): array
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        $classReflection = $this->reflectionProvider->getClass($className);
        $dataRecordClassName = $this->dataRecordResolver->resolveFullyQualifiedDataRecordClassNameFromControllerClassName($className);
        $tagValueNodes = [];
        // Fallback to Page if no data record was found
        if ($dataRecordClassName === null && $classReflection->is('PageController')) {
            $dataRecordClassName = 'Page';
        }
        // We can't really do anything at this point
        if ($dataRecordClassName === null) {
            return [];
        }
        $dataRecordType = new FullyQualifiedObjectType($dataRecordClassName);
        // Verify the dataRecord
        if ((new FullyQualifiedObjectType('SilverStripe\\CMS\\Model\\SiteTree'))->isSuperTypeOf($dataRecordType)->no()) {
            return [];
        }
        $dataRecordTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($dataRecordType);
        $parentClassName = $this->getParentClassName($classReflection);
        if ($parentClassName === 'SilverStripe\\CMS\\Controllers\\ContentController') {
            $tagValueNodes[] = new SpacingAwareTemplateTagValueNode(
                'T',
                $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($dataRecordType),
                '',
                'of'
            );

            $dataRecordTypeNode = new IdentifierTypeNode('T');
        }
        $genericTypeNode = new GenericTypeNode(
            $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(new FullyQualifiedObjectType($parentClassName)), // @phpstan-ignore-line
            [$dataRecordTypeNode]
        );
        $tagValueNodes[] = new ExtendsTagValueNode(
            $genericTypeNode,
            ''
        );
        return $tagValueNodes;
    }

    protected function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isAnonymous()) {
            return true;
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($class);
        // Skip if there is an existing extends
        if ($phpDocInfo->hasByName('@extends')) {
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
        return !$classReflection->is('SilverStripe\\CMS\\Controllers\\ContentController');
    }

    /**
     * @return class-string
     */
    private function getParentClassName(ClassReflection $classReflection): string
    {
        $parentClassReflection = $classReflection->getParentClass();

        if (!$parentClassReflection instanceof ClassReflection) {
            throw new ShouldNotHappenException();
        }

        return $parentClassReflection->getName();
    }
}
