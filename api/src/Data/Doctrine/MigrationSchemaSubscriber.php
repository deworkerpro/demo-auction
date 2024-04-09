<?php

declare(strict_types=1);

namespace App\Data\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use Override;

final readonly class MigrationSchemaSubscriber implements EventSubscriber
{
    public function __construct(
        private TableMetadataStorageConfiguration $configuration
    ) {}

    #[Override]
    public function getSubscribedEvents(): array
    {
        return [
            ToolEvents::postGenerateSchema => 'postGenerateSchema',
        ];
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $table = $args->getSchema()->createTable($this->configuration->getTableName());

        $table->addColumn(
            $this->configuration->getVersionColumnName(),
            'string',
            ['notnull' => true, 'length' => $this->configuration->getVersionColumnLength()],
        );

        $table->addColumn($this->configuration->getExecutedAtColumnName(), 'datetime', ['notnull' => false]);
        $table->addColumn($this->configuration->getExecutionTimeColumnName(), 'integer', ['notnull' => false]);

        $table->setPrimaryKey([$this->configuration->getVersionColumnName()]);
    }
}
