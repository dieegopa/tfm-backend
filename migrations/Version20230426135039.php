<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426135039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_university (user_id INT NOT NULL, university_id INT NOT NULL, INDEX IDX_71D4300A76ED395 (user_id), INDEX IDX_71D4300309D1878 (university_id), PRIMARY KEY(user_id, university_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_subject (user_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_A3C32070A76ED395 (user_id), INDEX IDX_A3C3207023EDC87 (subject_id), PRIMARY KEY(user_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_degree (user_id INT NOT NULL, degree_id INT NOT NULL, INDEX IDX_C2F1765EA76ED395 (user_id), INDEX IDX_C2F1765EB35C5756 (degree_id), PRIMARY KEY(user_id, degree_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_university ADD CONSTRAINT FK_71D4300A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_university ADD CONSTRAINT FK_71D4300309D1878 FOREIGN KEY (university_id) REFERENCES university (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_subject ADD CONSTRAINT FK_A3C32070A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_subject ADD CONSTRAINT FK_A3C3207023EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_degree ADD CONSTRAINT FK_C2F1765EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_degree ADD CONSTRAINT FK_C2F1765EB35C5756 FOREIGN KEY (degree_id) REFERENCES degree (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_university DROP FOREIGN KEY FK_71D4300A76ED395');
        $this->addSql('ALTER TABLE user_university DROP FOREIGN KEY FK_71D4300309D1878');
        $this->addSql('ALTER TABLE user_subject DROP FOREIGN KEY FK_A3C32070A76ED395');
        $this->addSql('ALTER TABLE user_subject DROP FOREIGN KEY FK_A3C3207023EDC87');
        $this->addSql('ALTER TABLE user_degree DROP FOREIGN KEY FK_C2F1765EA76ED395');
        $this->addSql('ALTER TABLE user_degree DROP FOREIGN KEY FK_C2F1765EB35C5756');
        $this->addSql('DROP TABLE user_university');
        $this->addSql('DROP TABLE user_subject');
        $this->addSql('DROP TABLE user_degree');
    }
}
