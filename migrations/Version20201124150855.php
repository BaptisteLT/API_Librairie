<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124150855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, exemplaire_id INT NOT NULL, created_at DATETIME NOT NULL, rendu VARBINARY(255) NOT NULL, INDEX IDX_364071D7A76ED395 (user_id), INDEX IDX_364071D75843AA21 (exemplaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exemplaire (id INT AUTO_INCREMENT NOT NULL, livre_id INT NOT NULL, num_exemplaire INT NOT NULL, date_ajout DATETIME DEFAULT NULL, INDEX IDX_5EF83C9237D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, genre_id INT NOT NULL, editeur_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, nb_pages INT DEFAULT NULL, annee_publication DATE DEFAULT NULL, INDEX IDX_AC634F9960BB6FE6 (auteur_id), INDEX IDX_AC634F994296D31F (genre_id), INDEX IDX_AC634F993375BD21 (editeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, etablissement VARCHAR(255) DEFAULT NULL, classe VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, genre VARBINARY(255) NOT NULL, adresse VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D75843AA21 FOREIGN KEY (exemplaire_id) REFERENCES exemplaire (id)');
        $this->addSql('ALTER TABLE exemplaire ADD CONSTRAINT FK_5EF83C9237D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F9960BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F994296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F993375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F9960BB6FE6');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F993375BD21');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D75843AA21');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F994296D31F');
        $this->addSql('ALTER TABLE exemplaire DROP FOREIGN KEY FK_5EF83C9237D925CB');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7A76ED395');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE exemplaire');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE user');
    }
}
