<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026145649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad_tag (ad_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_717EDDB44F34D596 (ad_id), INDEX IDX_717EDDB4BAD26311 (tag_id), PRIMARY KEY(ad_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad_tag ADD CONSTRAINT FK_717EDDB44F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ad_tag ADD CONSTRAINT FK_717EDDB4BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_ad DROP FOREIGN KEY FK_720BE9194F34D596');
        $this->addSql('ALTER TABLE tag_ad DROP FOREIGN KEY FK_720BE919BAD26311');
        $this->addSql('DROP TABLE tag_ad');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_ad (tag_id INT NOT NULL, ad_id INT NOT NULL, INDEX IDX_720BE919BAD26311 (tag_id), INDEX IDX_720BE9194F34D596 (ad_id), PRIMARY KEY(tag_id, ad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tag_ad ADD CONSTRAINT FK_720BE9194F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_ad ADD CONSTRAINT FK_720BE919BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ad_tag DROP FOREIGN KEY FK_717EDDB44F34D596');
        $this->addSql('ALTER TABLE ad_tag DROP FOREIGN KEY FK_717EDDB4BAD26311');
        $this->addSql('DROP TABLE ad_tag');
    }
}
