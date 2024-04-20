<?php

namespace Cambis\SilverstripeRector\PHPStan\Rector\Class_;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Rector\AbstractRector;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extension;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/syntro-opensource/silverstripe-phpstan#known-limitations--gotchas
 * @see \Cambis\SilverstripeRector\Tests\PHPStan\Rector\Class_\AddConfigAnnotationToConfigurablePropertiesRector\AddConfigAnnotationToConfigurablePropertiesRectorTest
 */
final class AddConfigAnnotationToConfigurablePropertiesRector extends AbstractRector
{
    private bool $hasChanged = false;

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds @config annotation to configurable properties for PHPStan.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    private static array $db = [
        'Bar' => 'Varchar(255)',
    ];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo extends \SilverStripe\ORM\DataObject
{
    /**
     * @config
     */
    private static array $db = [
        'Bar' => 'Varchar(255)',
    ];
}
CODE_SAMPLE
            ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    #[Override]
    public function getNodeTypes(): array
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

        foreach ($node->getProperties() as $property) {
            if (!$property->isPrivate()) {
                continue;
            }

            if (!$property->isStatic()) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            if ($phpDocInfo->hasByName('@config')) {
                continue;
            }

            if ($phpDocInfo->hasByName('@internal')) {
                continue;
            }

            $phpDocInfo->addPhpDocTagNode(new PhpDocTagNode('@config', new GenericTagValueNode('')));
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);
            $this->hasChanged = true;
        }

        return $this->hasChanged ? $node : null;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        $className = (string) $this->nodeNameResolver->getName($class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($classReflection->isSubclassOf(Controller::class)) {
            return false;
        }

        if ($classReflection->isSubclassOf(Extension::class)) {
            return false;
        }

        return !$classReflection->hasTraitUse(Configurable::class);
    }
}
