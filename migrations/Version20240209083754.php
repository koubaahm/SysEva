<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration automatiquement générée : Veuillez la modifier selon vos besoins !
 */
final class Version20240209083754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute l\'attribut confirmationToken à l\'entité User.';
    }

    public function up(Schema $schema): void
    {
        // cette méthode up() de migration est générée automatiquement, veuillez la modifier selon vos besoins

        // Ajoute la colonne confirmationToken à la table user
        $this->addSql('ALTER TABLE user ADD confirmation_token VARCHAR(255) DEFAULT NULL');

        // Le reste de votre code de migration reste inchangé
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, num_tel VARCHAR(15) NOT NULL, laboratoire VARCHAR(255) DEFAULT NULL, etat TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // cette méthode down() de migration est générée automatiquement, veuillez la modifier selon vos besoins
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
        
        // Ajoute la commande pour supprimer la colonne si la migration est annulée
        $this->addSql('ALTER TABLE user DROP COLUMN confirmation_token');
    }
}
