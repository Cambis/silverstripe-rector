<?php

declare(strict_types=1);

namespace Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_filter;

/**
 * @changelog https://github.com/silverstripe/silverstripe-framework/commit/15683cfd9839a2af012999e28a577592c6cdcab9
 *
 * @see \Cambis\SilverstripeRector\Tests\Silverstripe54\Rector\MethodCall\FormFieldExtendValidationResultToExtendRector\FormFieldExtendValidationResultToExtendRectorTest
 */
final class FormFieldExtendValidationResultToExtendRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ArgsAnalyzer $argsAnalyzer;
    /**
     * @readonly
     */
    private VariableNaming $variableNaming;
    public function __construct(ArgsAnalyzer $argsAnalyzer, VariableNaming $variableNaming)
    {
        $this->argsAnalyzer = $argsAnalyzer;
        $this->variableNaming = $variableNaming;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `FormField::extendValidationResult()` to `FormField::extend()`.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FooField extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        return $this->extendValidationResult(true, $validator);
    }         
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class FooField extends \SilverStripe\Forms\FormField
{
    public function validate($validator): bool
    {
        $result = true;

        $this->extend('updateValidationResult', true, $validator);

        return $result;
    }         
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, Return_::class];
    }

    /**
     * @param MethodCall|Return_ $node
     * @return Node|Node[]|null
     */
    public function refactor(Node $node)
    {
        if ($node instanceof Return_) {
            if (!$node->getAttribute(AttributeKey::SCOPE) instanceof Scope) {
                return null;
            }

            return $this->refactorReturn($node, $node->getAttribute(AttributeKey::SCOPE));
        }
        return $this->refactorMethodCall($node);
    }

    /**
     * @return ?Node[]
     */
    private function refactorReturn(Return_ $return, Scope $scope): ?array
    {
        if (!$return->expr instanceof MethodCall) {
            return null;
        }

        return $this->refactorReturnMethodCall($return->expr, $scope);
    }

    /**
     * @return ?Node[]
     */
    private function refactorReturnMethodCall(MethodCall $methodCall, Scope $scope): ?array
    {
        if ($this->shouldSkipMethodCall($methodCall)) {
            return null;
        }

        $resultArg = $methodCall->getArgs()[0] ?? null;

        if (!$resultArg instanceof Arg) {
            return null;
        }

        $extraStmts = [];
        $var = $resultArg->value;

        // If $result is not variable, make one
        if (!$var instanceof Variable) {
            $var = new Variable($this->variableNaming->createCountedValueName('result', $scope));

            $extraStmts = [
                new Expression(new Assign($var, $resultArg->value)),
                new Nop(),
            ];
        }

        return array_merge($extraStmts, [new Expression(
            $this->nodeFactory->createMethodCall(
                $methodCall->var,
                'extend',
                array_filter([
                    'updateValidationResult',
                    $var,
                    $methodCall->getArgs()[1] ?? null,
                ])
            ),
        ), new Nop(), new Return_($var)]);
    }

    private function refactorMethodCall(MethodCall $methodCall): ?MethodCall
    {
        if ($this->shouldSkipMethodCall($methodCall)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $methodCall->var,
            'extend',
            array_merge(['updateValidationResult'], $methodCall->getArgs())
        );
    }

    private function shouldSkipMethodCall(MethodCall $methodCall): bool
    {
        if (!$this->isObjectType($methodCall->var, new ObjectType('SilverStripe\Forms\FormField'))) {
            return true;
        }

        if (!$this->isName($methodCall->name, 'extendValidationResult')) {
            return true;
        }

        return $this->argsAnalyzer->hasNamedArg($methodCall->getArgs());
    }
}
