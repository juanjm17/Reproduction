<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618092901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_33EDEEA1A76ED395 ON song (user_id)');
        $this->addSql('ALTER TABLE songs_list ADD iduser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE songs_list ADD CONSTRAINT FK_A427D80B786A81FB FOREIGN KEY (iduser_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_A427D80B786A81FB ON songs_list (iduser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA1A76ED395');
        $this->addSql('DROP INDEX IDX_33EDEEA1A76ED395 ON song');
        $this->addSql('ALTER TABLE song DROP user_id');
        $this->addSql('ALTER TABLE songs_list DROP FOREIGN KEY FK_A427D80B786A81FB');
        $this->addSql('DROP INDEX IDX_A427D80B786A81FB ON songs_list');
        $this->addSql('ALTER TABLE songs_list DROP iduser_id');
    }
}
