<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129154358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F74E84967');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF675F31B');
        $this->addSql('ALTER TABLE image CHANGE author_id author_id INT DEFAULT NULL, CHANGE related_library_id related_library_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F74E84967 FOREIGN KEY (related_library_id) REFERENCES library_img (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY FK_C85FC209F675F31B');
        $this->addSql('ALTER TABLE library_img CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT FK_C85FC209F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF675F31B');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F74E84967');
        $this->addSql('ALTER TABLE image CHANGE author_id author_id INT NOT NULL, CHANGE related_library_id related_library_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F74E84967 FOREIGN KEY (related_library_id) REFERENCES library_img (id)');
        $this->addSql('ALTER TABLE library_img DROP FOREIGN KEY FK_C85FC209F675F31B');
        $this->addSql('ALTER TABLE library_img CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE library_img ADD CONSTRAINT FK_C85FC209F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }
}
