<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605084043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campaign_tracks (id INT AUTO_INCREMENT NOT NULL, track_title VARCHAR(255) NOT NULL, author_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD campaign_track_times_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64985EAE812 FOREIGN KEY (campaign_track_times_id) REFERENCES campaign_tracks (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64985EAE812 ON user (campaign_track_times_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64985EAE812');
        $this->addSql('DROP TABLE campaign_tracks');
        $this->addSql('DROP INDEX IDX_8D93D64985EAE812 ON user');
        $this->addSql('ALTER TABLE user DROP campaign_track_times_id');
    }
}
