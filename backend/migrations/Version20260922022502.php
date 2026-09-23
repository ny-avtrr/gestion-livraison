<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260922022502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(80) NOT NULL, telephone VARCHAR(20) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, adresse_livraison LONGTEXT NOT NULL, montant DOUBLE PRECISION NOT NULL, client_id INT NOT NULL, livreur_id INT NOT NULL, statut_actuel_id INT DEFAULT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67DF8646701 (livreur_id), INDEX IDX_6EEAA67DA831773D (statut_actuel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE livreur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, disponible TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, ordre INT NOT NULL, couleur VARCHAR(7) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE suivi_livraison (id INT AUTO_INCREMENT NOT NULL, date_changement DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, commande_id INT NOT NULL, statut_id INT NOT NULL, INDEX IDX_CFAC647182EA2E54 (commande_id), INDEX IDX_CFAC6471F6203804 (statut_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF8646701 FOREIGN KEY (livreur_id) REFERENCES livreur (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA831773D FOREIGN KEY (statut_actuel_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE suivi_livraison ADD CONSTRAINT FK_CFAC647182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE suivi_livraison ADD CONSTRAINT FK_CFAC6471F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF8646701');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA831773D');
        $this->addSql('ALTER TABLE suivi_livraison DROP FOREIGN KEY FK_CFAC647182EA2E54');
        $this->addSql('ALTER TABLE suivi_livraison DROP FOREIGN KEY FK_CFAC6471F6203804');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE suivi_livraison');
    }
}
