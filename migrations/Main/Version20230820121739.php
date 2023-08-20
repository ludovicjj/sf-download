<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820121739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add properties filename, originaleFilename and mimeType to ArticleReference Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_reference ADD filename VARCHAR(255) NOT NULL, ADD original_filename VARCHAR(255) NOT NULL, ADD mime_type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_reference DROP filename, DROP original_filename, DROP mime_type');
    }
}
