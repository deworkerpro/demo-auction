<?php

declare(strict_types=1);

namespace App\Rector;

use Attribute;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ConstructorPromotionExceptRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const string EXCEPT_CLASS_ATTRIBUTES = 'except_class_attributes';

    /**
     * @var list<class-string<Attribute>>
     */
    private array $exceptClassAttributes = [];

    public function __construct(
        private readonly ClassPropertyAssignToConstructorPromotionRector $origin
    ) {}

    #[Override]
    public function configure(array $configuration): void
    {
        /**
         * @var array{
         *     except_class_attributes?: list<class-string<Attribute>>,
         * } $configuration
         */
        $this->exceptClassAttributes = $configuration[self::EXCEPT_CLASS_ATTRIBUTES] ?? [];
    }

    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Constructor promotion.', []);
    }

    #[Override]
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        if ($this->exceptClassAttributes !== []) {
            foreach ($node->attrGroups as $attrGroup) {
                foreach ($attrGroup->attrs as $attribute) {
                    if (\in_array($attribute->name->toString(), $this->exceptClassAttributes, true)) {
                        return null;
                    }
                }
            }
        }

        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);
        if ($constructClassMethod === null) {
            return null;
        }

        $propertiesNames = array_map(
            static fn (Property $property) => $property->props[0]->name->toString(),
            $node->getProperties()
        );

        $constructorParamsNames = array_map(
            static fn (Param $param) => ($param->var instanceof Variable) ? $param->var->name : '',
            $constructClassMethod->getParams()
        );

        sort($propertiesNames);
        sort($constructorParamsNames);

        if ($propertiesNames !== $constructorParamsNames) {
            return null;
        }

        return $this->origin->refactor($node);
    }
}
