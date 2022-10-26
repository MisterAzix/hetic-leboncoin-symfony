<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026082937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_ad (tag_id INT NOT NULL, ad_id INT NOT NULL, INDEX IDX_720BE919BAD26311 (tag_id), INDEX IDX_720BE9194F34D596 (ad_id), PRIMARY KEY(tag_id, ad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_ad ADD CONSTRAINT FK_720BE919BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_ad ADD CONSTRAINT FK_720BE9194F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_ad DROP FOREIGN KEY FK_720BE919BAD26311');
        $this->addSql('ALTER TABLE tag_ad DROP FOREIGN KEY FK_720BE9194F34D596');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_ad');
    }
}
