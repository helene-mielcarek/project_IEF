<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129152633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY image_ibfk_1');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY image_ibfk_2');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F74E84967 FOREIGN KEY (related_library_id) REFERENCES library_img (id)');
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY library_img_ibfk_1');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT FK_C85FC209F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF675F31B');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F74E84967');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT image_ibfk_1 FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT image_ibfk_2 FOREIGN KEY (related_library_id) REFERENCES library_img (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY FK_C85FC209F675F31B');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT library_img_ibfk_1 FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
