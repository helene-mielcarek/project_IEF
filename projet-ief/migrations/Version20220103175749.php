<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103175749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant_event DROP FOREIGN KEY FK_FA1BA31E9D1C3019');
        $this->addSql('ALTER TABLE participant_user DROP FOREIGN KEY FK_5927C4779D1C3019');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_event');
        $this->addSql('DROP TABLE participant_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participant_event (participant_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_FA1BA31E9D1C3019 (participant_id), INDEX IDX_FA1BA31E71F7E88B (event_id), PRIMARY KEY(participant_id, event_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participant_user (participant_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5927C4779D1C3019 (participant_id), INDEX IDX_5927C477A76ED395 (user_id), PRIMARY KEY(participant_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participant_event ADD CONSTRAINT FK_FA1BA31E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_event ADD CONSTRAINT FK_FA1BA31E9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_user ADD CONSTRAINT FK_5927C4779D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_user ADD CONSTRAINT FK_5927C477A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
