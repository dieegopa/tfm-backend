<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426134513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE degree (id INT AUTO_INCREMENT NOT NULL, university_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_A7A36D63309D1878 (university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE degree_subject (degree_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_660C75BCB35C5756 (degree_id), INDEX IDX_660C75BC23EDC87 (subject_id), PRIMARY KEY(degree_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE degree ADD CONSTRAINT FK_A7A36D63309D1878 FOREIGN KEY (university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE degree_subject ADD CONSTRAINT FK_660C75BCB35C5756 FOREIGN KEY (degree_id) REFERENCES degree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE degree_subject ADD CONSTRAINT FK_660C75BC23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE degree DROP FOREIGN KEY FK_A7A36D63309D1878');
        $this->addSql('ALTER TABLE degree_subject DROP FOREIGN KEY FK_660C75BCB35C5756');
        $this->addSql('ALTER TABLE degree_subject DROP FOREIGN KEY FK_660C75BC23EDC87');
        $this->addSql('DROP TABLE degree');
        $this->addSql('DROP TABLE degree_subject');
        $this->addSql('DROP TABLE subject');
    }
}
