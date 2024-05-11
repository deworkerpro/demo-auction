<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511133428 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return '';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth_users RENAME COLUMN password_hash TO password_hash_value');
        $this->addSql('ALTER TABLE auth_users ADD password_hash_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('UPDATE auth_users SET password_hash_expires = date WHERE password_hash_value IS NOT NULL');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth_users DROP password_hash_expires');
        $this->addSql('ALTER TABLE auth_users RENAME COLUMN password_hash_value TO password_hash');
    }
}
