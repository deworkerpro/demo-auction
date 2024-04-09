<?php

declare(strict_types=1);

use App\Rector\ConstructorPromotionExceptRector;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Entity;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ConstructorPromotionExceptRector::class, [
        ConstructorPromotionExceptRector::EXCEPT_CLASS_ATTRIBUTES => [
            Entity::class,
            Embeddable::class,
        ],
    ]);
};
