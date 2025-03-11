<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\LinkField\Rector\StaticCall;

use Cambis\Silverstan\ConfigurationResolver\ConfigurationResolver;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\ValueObject\SilverstripeConstants;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Contract\DependencyInjection\RelatedConfigInterface;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function is_array;
use function is_bool;
use function is_string;

/**
 * @changelog https://github.com/silverstripe/silverstripe-linkfield/blob/4/docs/en/09_migrating/02_gorriecoe-migration.md
 *
 * @see \Cambis\SilverstripeRector\Tests\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector\GorriecoeLinkFieldToSilverstripeLinkFieldRectorTest
 */
final class GorriecoeLinkFieldToSilverstripeLinkFieldRector extends AbstractRector implements RelatedConfigInterface
{
    /**
     * @var array<string, string>
     */
    private const LINK_TYPES = [
        'URL' => 'SilverStripe\LinkField\Models\ExternalLink',
        'Email' => 'SilverStripe\LinkField\Models\EmailLink',
        'Phone' => 'SilverStripe\LinkField\Models\PhoneLink',
        'File' => 'SilverStripe\LinkField\Models\FileLink',
        'SiteTree' => 'SilverStripe\LinkField\Models\SiteTreeLink',
    ];

    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly ConfigurationResolver $configurationResolver,
        private readonly ValueResolver $valueResolver
    ) {
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `gorriecoe\LinkField\LinkField` to `SilverStripe\LinkField\Form\LinkField`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\gorriecoe\LinkField\LinkField::create('Link', 'Link', $this, ['types' => ['SiteTree']]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\SilverStripe\LinkField\Form\LinkField::create('Link', 'Link')
    ->setAllowedTypes([\SilverStripe\LinkField\Models\SiteTreeLink::class]);
CODE_SAMPLE
            ),
        ]);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [New_::class, StaticCall::class];
    }

    /**
     * @param New_|StaticCall $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (!$this->isName($node->class, 'gorriecoe\LinkField\LinkField')) {
            return null;
        }

        if ($node instanceof StaticCall && !$this->isName($node->name, SilverstripeConstants::METHOD_CREATE)) {
            return null;
        }

        if ($this->argsAnalyzer->hasNamedArg($node->getArgs())) {
            return null;
        }

        $node->class = $this->resolveFormFieldClass($node);

        // Remove parent argument
        if (isset($node->args[2])) {
            unset($node->args[2]);
        }

        if (isset($node->args[3])) {
            return $this->refactorLinkConfig($node);
        }

        return $node;
    }

    #[Override]
    public static function getConfigFile(): string
    {
        return SilverstripeSetList::WITH_RECTOR_SERVICES;
    }

    /**
     * Resolve the parent.
     */
    private function resolveParentClass(Type $type): ?ClassReflection
    {
        foreach ($type->getObjectClassReflections() as $classReflection) {
            if (!$classReflection->is('SilverStripe\ORM\DataObject')) {
                continue;
            }

            return $classReflection;
        }

        return null;
    }

    /**
     * Resolve the class of form field to use. Single relations should use `LinkField`, while multi relations should use `MultiLinkField`.
     */
    private function resolveFormFieldClass(New_|StaticCall $node): FullyQualified
    {
        $name = $node->getArgs()[0] ?? null;

        if (!$name instanceof Arg) {
            return new FullyQualified('SilverStripe\LinkField\Form\LinkField');
        }

        $parent = $node->getArgs()[2] ?? null;

        if (!$parent instanceof Arg) {
            return new FullyQualified('SilverStripe\LinkField\Form\LinkField');
        }

        $nameValue = $this->valueResolver->getValue($name);
        $parentClass = $this->resolveParentClass($this->getType($parent->value));

        // Couldn't resolve name, fallback
        if (!is_string($nameValue)) {
            return new FullyQualified('SilverStripe\LinkField\Form\LinkField');
        }

        // Couldn't resolve parent, fallback
        if (!$parentClass instanceof ClassReflection) {
            return new FullyQualified('SilverStripe\LinkField\Form\LinkField');
        }

        $hasMany = $this->configurationResolver->get($parentClass->getName(), 'has_many');

        // Check if link is a has many
        if (is_array($hasMany) && isset($hasMany[$nameValue])) {
            return new FullyQualified('SilverStripe\LinkField\Form\MultiLinkField');
        }

        $manyMany = $this->configurationResolver->get($parentClass->getName(), 'many_many');

        // Check if link is a many many
        if (is_array($manyMany) && isset($manyMany[$nameValue])) {
            return new FullyQualified('SilverStripe\LinkField\Form\MultiLinkField');
        }

        // Fallback
        return new FullyQualified('SilverStripe\LinkField\Form\LinkField');
    }

    private function refactorLinkConfig(New_|StaticCall $node): Node
    {
        $linkConfigArg = $node->getArgs()[3] ?? null;

        if (!$linkConfigArg instanceof Arg) {
            return $node;
        }

        // Get the value of linkConfig
        $linkConfig = $this->valueResolver->getValue($linkConfigArg);

        // Remove linkConfig argument
        unset($node->args[3]);

        if (!is_array($linkConfig)) {
            return $node;
        }

        // Migrate types to LinkField::setAllowedTypes()
        if (isset($linkConfig['types']) && is_array($linkConfig['types'])) {
            $allowedTypes = [];

            foreach ($linkConfig['types'] as $allowedType) {
                if (!isset(self::LINK_TYPES[$allowedType])) {
                    continue;
                }

                $allowedTypes[] = new ClassConstFetch(new FullyQualified(self::LINK_TYPES[$allowedType]), 'class');
            }

            $node = $this->nodeFactory->createMethodCall(
                $node,
                'setAllowedTypes',
                [$this->nodeFactory->createArray($allowedTypes)]
            );
        }

        // Migrate title_display to LinkField::setExcludeLinkTextField()
        if (isset($linkConfig['title_display'])) {
            $titleDisplay = $linkConfig['title_display'];

            if (is_bool($titleDisplay)) {
                $node = $this->nodeFactory->createMethodCall(
                    $node,
                    'setExcludeLinkTextField',
                    [$titleDisplay]
                );
            }
        }

        return $node;
    }
}
