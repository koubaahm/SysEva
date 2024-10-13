<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521120746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD publication_date DATE DEFAULT NULL, ADD journal_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE criteres ADD grille_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE criteres ADD CONSTRAINT FK_E913A5C5985C2966 FOREIGN KEY (grille_id) REFERENCES grille (id)');
        $this->addSql('CREATE INDEX IDX_E913A5C5985C2966 ON criteres (grille_id)');
        $this->addSql('ALTER TABLE evaluation ADD commentaire LONGTEXT NOT NULL, ADD submite TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE grille DROP FOREIGN KEY FK_D452165FA6EB9800');
        $this->addSql('DROP INDEX IDX_D452165FA6EB9800 ON grille');
        $this->addSql('ALTER TABLE grille DROP criteres_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE grille ADD criteres_id INT NOT NULL');
        $this->addSql('ALTER TABLE grille ADD CONSTRAINT FK_D452165FA6EB9800 FOREIGN KEY (criteres_id) REFERENCES criteres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D452165FA6EB9800 ON grille (criteres_id)');
        $this->addSql('ALTER TABLE article DROP publication_date, DROP journal_name');
        $this->addSql('ALTER TABLE evaluation DROP commentaire, DROP submite');
        $this->addSql('ALTER TABLE criteres DROP FOREIGN KEY FK_E913A5C5985C2966');
        $this->addSql('DROP INDEX IDX_E913A5C5985C2966 ON criteres');
        $this->addSql('ALTER TABLE criteres DROP grille_id');
    }
}
