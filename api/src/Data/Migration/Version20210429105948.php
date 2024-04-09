<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429105948 extends AbstractMigration
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
        $this->addSql('CREATE TABLE oauth_auth_codes (identifier VARCHAR(80) NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier UUID NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('COMMENT ON COLUMN oauth_auth_codes.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE oauth_refresh_tokens (identifier VARCHAR(80) NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier UUID NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('COMMENT ON COLUMN oauth_refresh_tokens.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE oauth_auth_codes');
        $this->addSql('DROP TABLE oauth_refresh_tokens');
    }
}
