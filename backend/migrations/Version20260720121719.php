<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260720121719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE creneau (id INT AUTO_INCREMENT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, actif TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE echange_recompense (id INT AUTO_INCREMENT NOT NULL, date_echange DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, utilisateur_id INT NOT NULL, recompense_id INT NOT NULL, INDEX IDX_67A27A4EFB88E14F (utilisateur_id), INDEX IDX_67A27A4E4D714096 (recompense_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE historique_points (id INT AUTO_INCREMENT NOT NULL, points_gagnes INT NOT NULL, type_operation VARCHAR(255) NOT NULL, date_operation DATETIME NOT NULL, solde_apres INT NOT NULL, utilisateur_id INT NOT NULL, reservation_id INT DEFAULT NULL, INDEX IDX_EEF82E75FB88E14F (utilisateur_id), INDEX IDX_EEF82E75B83297E7 (reservation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE programme_fidelite (id INT AUTO_INCREMENT NOT NULL, points_par_resa INT NOT NULL, seuil_recompense INT NOT NULL, actif TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, type VARCHAR(255) NOT NULL, seuil_points INT NOT NULL, programme_id INT NOT NULL, INDEX IDX_1E9BC0DE62BB7AEE (programme_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_reservation DATE NOT NULL, nb_couverts SMALLINT NOT NULL, statut VARCHAR(255) NOT NULL, honoree TINYINT NOT NULL, created_at DATETIME NOT NULL, utilisateur_id INT NOT NULL, table_id INT NOT NULL, creneau_id INT NOT NULL, INDEX IDX_42C84955FB88E14F (utilisateur_id), INDEX IDX_42C84955ECFF285C (table_id), INDEX IDX_42C849557D0729A9 (creneau_id), INDEX idx_date (date_reservation), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE table_resto (id INT AUTO_INCREMENT NOT NULL, numero SMALLINT NOT NULL, capacite SMALLINT NOT NULL, disponible TINYINT NOT NULL, UNIQUE INDEX UNIQ_85DD98C9F55AE19E (numero), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE echange_recompense ADD CONSTRAINT FK_67A27A4EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE echange_recompense ADD CONSTRAINT FK_67A27A4E4D714096 FOREIGN KEY (recompense_id) REFERENCES recompense (id)');
        $this->addSql('ALTER TABLE historique_points ADD CONSTRAINT FK_EEF82E75FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historique_points ADD CONSTRAINT FK_EEF82E75B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme_fidelite (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ECFF285C FOREIGN KEY (table_id) REFERENCES table_resto (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echange_recompense DROP FOREIGN KEY FK_67A27A4EFB88E14F');
        $this->addSql('ALTER TABLE echange_recompense DROP FOREIGN KEY FK_67A27A4E4D714096');
        $this->addSql('ALTER TABLE historique_points DROP FOREIGN KEY FK_EEF82E75FB88E14F');
        $this->addSql('ALTER TABLE historique_points DROP FOREIGN KEY FK_EEF82E75B83297E7');
        $this->addSql('ALTER TABLE recompense DROP FOREIGN KEY FK_1E9BC0DE62BB7AEE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FB88E14F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ECFF285C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557D0729A9');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('DROP TABLE echange_recompense');
        $this->addSql('DROP TABLE historique_points');
        $this->addSql('DROP TABLE programme_fidelite');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE table_resto');
        $this->addSql('DROP TABLE utilisateur');
    }
}
