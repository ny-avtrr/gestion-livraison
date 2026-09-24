<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260924032908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE adresse adresse LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande CHANGE montant montant DOUBLE PRECISION DEFAULT NULL, CHANGE livreur_id livreur_id INT DEFAULT NULL, CHANGE statut_actuel_id statut_actuel_id INT NOT NULL');
        $this->addSql('ALTER TABLE statut CHANGE couleur couleur VARCHAR(7) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE adresse adresse LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE commande CHANGE montant montant DOUBLE PRECISION NOT NULL, CHANGE livreur_id livreur_id INT NOT NULL, CHANGE statut_actuel_id statut_actuel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE statut CHANGE couleur couleur VARCHAR(7) NOT NULL');
    }
}
