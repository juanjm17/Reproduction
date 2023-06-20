<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618101407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE songs_list_song (songs_list_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_D91175586FFD009 (songs_list_id), INDEX IDX_D911755A0BDB2F3 (song_id), PRIMARY KEY(songs_list_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE songs_list_song ADD CONSTRAINT FK_D91175586FFD009 FOREIGN KEY (songs_list_id) REFERENCES songs_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE songs_list_song ADD CONSTRAINT FK_D911755A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE songs_list_song DROP FOREIGN KEY FK_D91175586FFD009');
        $this->addSql('ALTER TABLE songs_list_song DROP FOREIGN KEY FK_D911755A0BDB2F3');
        $this->addSql('DROP TABLE songs_list_song');
    }
}
