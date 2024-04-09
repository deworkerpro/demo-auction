<?php

declare(strict_types=1);

namespace App\Rector;

use App\Rector\Tests\ConstructorPromotionExceptRector\ConstructorPromotionExceptRectorTest;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see ConstructorPromotionExceptRectorTest
 */
final class ConstructorPromotionExceptRector extends AbstractRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('// @todo fill the description', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                    // @todo fill code before
                    CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
                    // @todo fill code after
                    CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    #[Override]
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        // @todo change the node

        return $node;
    }
}
