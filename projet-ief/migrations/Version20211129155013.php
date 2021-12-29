<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129155013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY FK_C85FC209D774A626');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT FK_C85FC209D774A626 FOREIGN KEY (related_event_id) REFERENCES event (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY FK_C85FC209D774A626');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT FK_C85FC209D774A626 FOREIGN KEY (related_event_id) REFERENCES event (id)');
    }
}
