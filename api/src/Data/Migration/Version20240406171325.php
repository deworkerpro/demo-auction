<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406171325 extends AbstractMigration
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
        $this->addSql('COMMENT ON COLUMN auth_user_networks.user_id IS \'\'');
        $this->addSql('ALTER TABLE auth_users ALTER email TYPE VARCHAR');
        $this->addSql('ALTER TABLE auth_users ALTER email TYPE VARCHAR');
        $this->addSql('ALTER TABLE auth_users ALTER new_email TYPE VARCHAR');
        $this->addSql('ALTER TABLE auth_users ALTER new_email TYPE VARCHAR');
        $this->addSql('COMMENT ON COLUMN auth_users.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.date IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.email IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.status IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.new_email IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.role IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.join_confirm_token_expires IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.password_reset_token_expires IS \'\'');
        $this->addSql('COMMENT ON COLUMN auth_users.new_email_token_expires IS \'\'');
        $this->addSql('COMMENT ON COLUMN oauth_auth_codes.expiry_date_time IS \'\'');
        $this->addSql('COMMENT ON COLUMN oauth_refresh_tokens.expiry_date_time IS \'\'');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs;
        $this->addSql('ALTER TABLE auth_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE auth_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE auth_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE auth_users ALTER new_email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN auth_users.id IS \'(DC2Type:auth_user_id)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.email IS \'(DC2Type:auth_user_email)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.status IS \'(DC2Type:auth_user_status)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.new_email IS \'(DC2Type:auth_user_email)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.role IS \'(DC2Type:auth_user_role)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.join_confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.password_reset_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.new_email_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_user_networks.user_id IS \'(DC2Type:auth_user_id)\'');
        $this->addSql('COMMENT ON COLUMN oauth_refresh_tokens.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN oauth_auth_codes.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
    }
}
