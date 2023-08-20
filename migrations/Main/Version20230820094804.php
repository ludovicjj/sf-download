<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820094804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add properties excelFilename and imageFilename to Article Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD excel_filename VARCHAR(255) DEFAULT NULL, CHANGE filename image_filename VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD filename VARCHAR(255) DEFAULT NULL, DROP image_filename, DROP excel_filename');
    }
}
