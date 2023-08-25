<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825201728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add property forceChangePassword to User Entity and define default value';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD force_change_password TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE user SET force_change_password = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP force_change_password');
    }
}
