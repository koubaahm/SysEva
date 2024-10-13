<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315134326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grille (id INT AUTO_INCREMENT NOT NULL, annee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE criteres ADD grille_id INT NOT NULL');
        $this->addSql('ALTER TABLE criteres ADD CONSTRAINT FK_E913A5C5985C2966 FOREIGN KEY (grille_id) REFERENCES grille (id)');
        $this->addSql('CREATE INDEX IDX_E913A5C5985C2966 ON criteres (grille_id)');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575A6EB9800');
        $this->addSql('DROP INDEX IDX_1323A575A6EB9800 ON evaluation');
        $this->addSql('ALTER TABLE evaluation CHANGE criteres_id grille_id INT NOT NULL');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575985C2966 FOREIGN KEY (grille_id) REFERENCES grille (id)');
        $this->addSql('CREATE INDEX IDX_1323A575985C2966 ON evaluation (grille_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE criteres DROP FOREIGN KEY FK_E913A5C5985C2966');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575985C2966');
        $this->addSql('DROP TABLE grille');
        $this->addSql('DROP INDEX IDX_1323A575985C2966 ON evaluation');
        $this->addSql('ALTER TABLE evaluation CHANGE grille_id criteres_id INT NOT NULL');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575A6EB9800 FOREIGN KEY (criteres_id) REFERENCES criteres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1323A575A6EB9800 ON evaluation (criteres_id)');
        $this->addSql('DROP INDEX IDX_E913A5C5985C2966 ON criteres');
        $this->addSql('ALTER TABLE criteres DROP grille_id');
    }
}
