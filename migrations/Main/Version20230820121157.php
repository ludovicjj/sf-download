<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820121157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ArticleReference Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_reference (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, INDEX IDX_749619377294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_reference ADD CONSTRAINT FK_749619377294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_reference DROP FOREIGN KEY FK_749619377294869C');
        $this->addSql('DROP TABLE article_reference');
    }
}
