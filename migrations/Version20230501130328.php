<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501130328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, degree_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, number INT NOT NULL, INDEX IDX_169E6FB9B35C5756 (degree_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9B35C5756 FOREIGN KEY (degree_id) REFERENCES degree (id)');
        $this->addSql('ALTER TABLE subject ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A591CC992 ON subject (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9B35C5756');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP INDEX IDX_FBCE3E7A591CC992 ON subject');
        $this->addSql('ALTER TABLE subject DROP course_id');
    }
}
