<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618160630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_replays ADD track_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_replays ADD CONSTRAINT FK_2990948A5ED23C43 FOREIGN KEY (track_id) REFERENCES player_tracks (id)');
        $this->addSql('CREATE INDEX IDX_2990948A5ED23C43 ON player_replays (track_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_replays DROP FOREIGN KEY FK_2990948A5ED23C43');
        $this->addSql('DROP INDEX IDX_2990948A5ED23C43 ON player_replays');
        $this->addSql('ALTER TABLE player_replays DROP track_id');
    }
}
