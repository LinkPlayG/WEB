<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319065722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campus DROP FOREIGN KEY campus_ibfk_1');
        $this->addSql('ALTER TABLE pilote_de_promotion DROP FOREIGN KEY pilote_de_promotion_ibfk_1');
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY administrateur_ibfk_1');
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT NOT NULL, offre_de_stage_id INT NOT NULL, date_candidature DATETIME NOT NULL, date_reponse DATETIME DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_E33BD3B8DDEAB1A3 (etudiant_id), INDEX IDX_E33BD3B81D3C911F (offre_de_stage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT NOT NULL, promotion_id INT NOT NULL, adresse_id INT DEFAULT NULL, nom_etudiant VARCHAR(255) NOT NULL, prenom_etudiant VARCHAR(255) NOT NULL, INDEX IDX_717E22E3139DF194 (promotion_id), INDEX IDX_717E22E34DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteur_dactivite (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B81D3C911F FOREIGN KEY (offre_de_stage_id) REFERENCES offre_de_stage (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E34DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composer DROP FOREIGN KEY composer_ibfk_1');
        $this->addSql('ALTER TABLE composer DROP FOREIGN KEY composer_ibfk_2');
        $this->addSql('ALTER TABLE faire_partie DROP FOREIGN KEY faire_partie_ibfk_1');
        $this->addSql('ALTER TABLE faire_partie DROP FOREIGN KEY faire_partie_ibfk_2');
        $this->addSql('ALTER TABLE candidater DROP FOREIGN KEY candidater_ibfk_1');
        $this->addSql('ALTER TABLE candidater DROP FOREIGN KEY candidater_ibfk_2');
        $this->addSql('ALTER TABLE exiger DROP FOREIGN KEY exiger_ibfk_1');
        $this->addSql('ALTER TABLE exiger DROP FOREIGN KEY exiger_ibfk_2');
        $this->addSql('ALTER TABLE dans_la_ville DROP FOREIGN KEY dans_la_ville_ibfk_1');
        $this->addSql('ALTER TABLE dans_la_ville DROP FOREIGN KEY dans_la_ville_ibfk_2');
        $this->addSql('ALTER TABLE dans_le_pays DROP FOREIGN KEY dans_le_pays_ibfk_1');
        $this->addSql('ALTER TABLE dans_le_pays DROP FOREIGN KEY dans_le_pays_ibfk_2');
        $this->addSql('ALTER TABLE gérer DROP FOREIGN KEY gérer_ibfk_1');
        $this->addSql('ALTER TABLE gérer DROP FOREIGN KEY gérer_ibfk_2');
        $this->addSql('ALTER TABLE etre_situer DROP FOREIGN KEY etre_situer_ibfk_1');
        $this->addSql('ALTER TABLE etre_situer DROP FOREIGN KEY etre_situer_ibfk_2');
        $this->addSql('ALTER TABLE étudiant DROP FOREIGN KEY étudiant_ibfk_1');
        $this->addSql('ALTER TABLE étudiant DROP FOREIGN KEY étudiant_ibfk_2');
        $this->addSql('DROP TABLE composer');
        $this->addSql('DROP TABLE addresse');
        $this->addSql('DROP TABLE compétence');
        $this->addSql('DROP TABLE faire_partie');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE candidater');
        $this->addSql('DROP TABLE exiger');
        $this->addSql('DROP TABLE dans_la_ville');
        $this->addSql('DROP TABLE dans_le_pays');
        $this->addSql('DROP TABLE gérer');
        $this->addSql('DROP TABLE etre_situer');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE secteur_d_activité');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE étudiant');
        $this->addSql('DROP INDEX id_utilisateur ON administrateur');
        $this->addSql('DROP INDEX `primary` ON administrateur');
        $this->addSql('ALTER TABLE administrateur ADD id INT NOT NULL, ADD prenom_admin VARCHAR(255) NOT NULL, DROP id_admin, DROP id_utilisateur, DROP prénom_admin, CHANGE nom_admin nom_admin VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE administrateur ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX id_addresse ON campus');
        $this->addSql('ALTER TABLE campus ADD id INT AUTO_INCREMENT NOT NULL, DROP id_campus, DROP id_addresse, DROP nom_campus, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE entreprise ADD id INT AUTO_INCREMENT NOT NULL, DROP id_entreprise, DROP description_entreprise, DROP ville_entreprise, DROP nom_entreprise, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE offre_de_stage DROP FOREIGN KEY offre_de_stage_ibfk_1');
        $this->addSql('ALTER TABLE offre_de_stage DROP FOREIGN KEY offre_de_stage_ibfk_2');
        $this->addSql('DROP INDEX id_entreprise ON offre_de_stage');
        $this->addSql('DROP INDEX id_pilote ON offre_de_stage');
        $this->addSql('ALTER TABLE offre_de_stage ADD id INT AUTO_INCREMENT NOT NULL, DROP id_offre, DROP id_pilote, DROP id_entreprise, DROP titre_offre, DROP description_offre, DROP durée_stage, DROP date_début_stage, DROP date_fin_stage, DROP salaire, DROP statut_offre, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX id_utilisateur ON pilote_de_promotion');
        $this->addSql('DROP INDEX `primary` ON pilote_de_promotion');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD id INT NOT NULL, ADD prenom_pilote VARCHAR(255) NOT NULL, DROP id_pilote, DROP id_utilisateur, DROP prénom_pilote, CHANGE nom_pilote nom_pilote VARCHAR(255) NOT NULL, CHANGE email_pilote email_pilote VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD CONSTRAINT FK_2320087EBF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_2');
        $this->addSql('DROP INDEX id_campus ON promotion');
        $this->addSql('DROP INDEX id_pilote ON promotion');
        $this->addSql('ALTER TABLE promotion ADD id INT AUTO_INCREMENT NOT NULL, DROP id_promotion, DROP id_campus, DROP id_pilote, DROP id_étudiant, DROP Date_début_promo, DROP date_fin_promo, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8BF396750');
        $this->addSql('ALTER TABLE pilote_de_promotion DROP FOREIGN KEY FK_2320087EBF396750');
        $this->addSql('CREATE TABLE composer (id_étudiant INT NOT NULL, id_promotion INT NOT NULL, INDEX id_promotion (id_promotion), INDEX IDX_987306D8DE2338F6 (id_étudiant), PRIMARY KEY(id_étudiant, id_promotion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE addresse (id_addresse INT NOT NULL, nom_addresse VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, addresse_complète VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, logitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id_addresse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE compétence (Id_compétence INT NOT NULL, nom_compétence VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(Id_compétence)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE faire_partie (id_entreprise INT NOT NULL, id_secteur INT NOT NULL, INDEX id_secteur (id_secteur), INDEX IDX_27880B89A8937AB7 (id_entreprise), PRIMARY KEY(id_entreprise, id_secteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pays (nom_pays VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(nom_pays)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE candidater (id_offre INT NOT NULL, id_étudiant INT NOT NULL, date_candidature DATE DEFAULT NULL, date_réponse DATE DEFAULT NULL, statut VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, INDEX id_étudiant (id_étudiant), INDEX IDX_1D70C89A4103C75F (id_offre), PRIMARY KEY(id_offre, id_étudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE exiger (id_offre INT NOT NULL, Id_compétence INT NOT NULL, INDEX Id_compétence (Id_compétence), INDEX IDX_8D6E7E9A4103C75F (id_offre), PRIMARY KEY(id_offre, Id_compétence)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dans_la_ville (id_addresse INT NOT NULL, nom_ville VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, INDEX nom_ville (nom_ville), INDEX IDX_1242BD7625B36B16 (id_addresse), PRIMARY KEY(id_addresse, nom_ville)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dans_le_pays (nom_ville VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, nom_pays VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, INDEX nom_pays (nom_pays), INDEX IDX_F06AF189E93B4556 (nom_ville), PRIMARY KEY(nom_ville, nom_pays)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gérer (id_pilote INT NOT NULL, id_admin INT NOT NULL, INDEX id_admin (id_admin), INDEX IDX_CB1169094C05E130 (id_pilote), PRIMARY KEY(id_pilote, id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etre_situer (id_entreprise INT NOT NULL, id_addresse INT NOT NULL, INDEX id_addresse (id_addresse), INDEX IDX_2F170A77A8937AB7 (id_entreprise), PRIMARY KEY(id_entreprise, id_addresse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id_utilisateur INT NOT NULL, mail_utilisateur VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, mot_de_passe VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, role VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, date_creation DATETIME DEFAULT NULL, PRIMARY KEY(id_utilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE secteur_d_activité (id_secteur INT NOT NULL, nom_secteur VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id_secteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ville (nom_ville VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(nom_ville)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE étudiant (id_étudiant INT NOT NULL, id_addresse INT NOT NULL, id_utilisateur INT NOT NULL, nom_étudiant VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, prénom_étudiant VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, INDEX id_addresse (id_addresse), UNIQUE INDEX id_utilisateur (id_utilisateur), PRIMARY KEY(id_étudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE composer ADD CONSTRAINT composer_ibfk_1 FOREIGN KEY (id_étudiant) REFERENCES étudiant (id_étudiant) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE composer ADD CONSTRAINT composer_ibfk_2 FOREIGN KEY (id_promotion) REFERENCES promotion (id_promotion) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE faire_partie ADD CONSTRAINT faire_partie_ibfk_1 FOREIGN KEY (id_entreprise) REFERENCES entreprise (id_entreprise) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE faire_partie ADD CONSTRAINT faire_partie_ibfk_2 FOREIGN KEY (id_secteur) REFERENCES secteur_d_activité (id_secteur) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE candidater ADD CONSTRAINT candidater_ibfk_1 FOREIGN KEY (id_offre) REFERENCES offre_de_stage (id_offre) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE candidater ADD CONSTRAINT candidater_ibfk_2 FOREIGN KEY (id_étudiant) REFERENCES étudiant (id_étudiant) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exiger ADD CONSTRAINT exiger_ibfk_1 FOREIGN KEY (id_offre) REFERENCES offre_de_stage (id_offre) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exiger ADD CONSTRAINT exiger_ibfk_2 FOREIGN KEY (Id_compétence) REFERENCES compétence (Id_compétence) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dans_la_ville ADD CONSTRAINT dans_la_ville_ibfk_1 FOREIGN KEY (id_addresse) REFERENCES addresse (id_addresse) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dans_la_ville ADD CONSTRAINT dans_la_ville_ibfk_2 FOREIGN KEY (nom_ville) REFERENCES ville (nom_ville) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dans_le_pays ADD CONSTRAINT dans_le_pays_ibfk_1 FOREIGN KEY (nom_ville) REFERENCES ville (nom_ville) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dans_le_pays ADD CONSTRAINT dans_le_pays_ibfk_2 FOREIGN KEY (nom_pays) REFERENCES pays (nom_pays) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE gérer ADD CONSTRAINT gérer_ibfk_1 FOREIGN KEY (id_pilote) REFERENCES pilote_de_promotion (id_pilote) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE gérer ADD CONSTRAINT gérer_ibfk_2 FOREIGN KEY (id_admin) REFERENCES administrateur (id_admin) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE etre_situer ADD CONSTRAINT etre_situer_ibfk_1 FOREIGN KEY (id_entreprise) REFERENCES entreprise (id_entreprise) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE etre_situer ADD CONSTRAINT etre_situer_ibfk_2 FOREIGN KEY (id_addresse) REFERENCES addresse (id_addresse) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE étudiant ADD CONSTRAINT étudiant_ibfk_1 FOREIGN KEY (id_addresse) REFERENCES addresse (id_addresse) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE étudiant ADD CONSTRAINT étudiant_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8DDEAB1A3');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B81D3C911F');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3139DF194');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E34DE7DC5C');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BF396750');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE secteur_dactivite');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX `PRIMARY` ON pilote_de_promotion');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD id_utilisateur INT NOT NULL, ADD prénom_pilote VARCHAR(50) DEFAULT NULL, DROP prenom_pilote, CHANGE nom_pilote nom_pilote VARCHAR(50) DEFAULT NULL, CHANGE email_pilote email_pilote VARCHAR(50) DEFAULT NULL, CHANGE id id_pilote INT NOT NULL');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD CONSTRAINT pilote_de_promotion_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX id_utilisateur ON pilote_de_promotion (id_utilisateur)');
        $this->addSql('ALTER TABLE pilote_de_promotion ADD PRIMARY KEY (id_pilote)');
        $this->addSql('DROP INDEX `PRIMARY` ON administrateur');
        $this->addSql('ALTER TABLE administrateur ADD id_utilisateur INT NOT NULL, ADD prénom_admin VARCHAR(50) DEFAULT NULL, DROP prenom_admin, CHANGE nom_admin nom_admin VARCHAR(50) DEFAULT NULL, CHANGE id id_admin INT NOT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT administrateur_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX id_utilisateur ON administrateur (id_utilisateur)');
        $this->addSql('ALTER TABLE administrateur ADD PRIMARY KEY (id_admin)');
        $this->addSql('ALTER TABLE offre_de_stage MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON offre_de_stage');
        $this->addSql('ALTER TABLE offre_de_stage ADD id_offre INT NOT NULL, ADD id_pilote INT NOT NULL, ADD id_entreprise INT NOT NULL, ADD titre_offre VARCHAR(50) DEFAULT NULL, ADD description_offre VARCHAR(50) DEFAULT NULL, ADD durée_stage INT DEFAULT NULL, ADD date_début_stage DATE DEFAULT NULL, ADD date_fin_stage DATE DEFAULT NULL, ADD salaire INT DEFAULT NULL, ADD statut_offre VARCHAR(50) DEFAULT NULL, DROP id');
        $this->addSql('ALTER TABLE offre_de_stage ADD CONSTRAINT offre_de_stage_ibfk_1 FOREIGN KEY (id_pilote) REFERENCES pilote_de_promotion (id_pilote) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE offre_de_stage ADD CONSTRAINT offre_de_stage_ibfk_2 FOREIGN KEY (id_entreprise) REFERENCES entreprise (id_entreprise) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX id_entreprise ON offre_de_stage (id_entreprise)');
        $this->addSql('CREATE INDEX id_pilote ON offre_de_stage (id_pilote)');
        $this->addSql('ALTER TABLE offre_de_stage ADD PRIMARY KEY (id_offre)');
        $this->addSql('ALTER TABLE campus MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON campus');
        $this->addSql('ALTER TABLE campus ADD id_campus INT NOT NULL, ADD id_addresse INT NOT NULL, ADD nom_campus VARCHAR(50) DEFAULT NULL, DROP id');
        $this->addSql('ALTER TABLE campus ADD CONSTRAINT campus_ibfk_1 FOREIGN KEY (id_addresse) REFERENCES addresse (id_addresse) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX id_addresse ON campus (id_addresse)');
        $this->addSql('ALTER TABLE campus ADD PRIMARY KEY (id_campus)');
        $this->addSql('ALTER TABLE entreprise MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON entreprise');
        $this->addSql('ALTER TABLE entreprise ADD id_entreprise INT NOT NULL, ADD description_entreprise VARCHAR(50) DEFAULT NULL, ADD ville_entreprise VARCHAR(50) DEFAULT NULL, ADD nom_entreprise VARCHAR(50) DEFAULT NULL, DROP id');
        $this->addSql('ALTER TABLE entreprise ADD PRIMARY KEY (id_entreprise)');
        $this->addSql('ALTER TABLE promotion MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON promotion');
        $this->addSql('ALTER TABLE promotion ADD id_promotion INT NOT NULL, ADD id_campus INT NOT NULL, ADD id_pilote INT NOT NULL, ADD id_étudiant INT DEFAULT NULL, ADD Date_début_promo DATE DEFAULT NULL, ADD date_fin_promo DATE DEFAULT NULL, DROP id');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_campus) REFERENCES campus (id_campus) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_2 FOREIGN KEY (id_pilote) REFERENCES pilote_de_promotion (id_pilote) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX id_campus ON promotion (id_campus)');
        $this->addSql('CREATE INDEX id_pilote ON promotion (id_pilote)');
        $this->addSql('ALTER TABLE promotion ADD PRIMARY KEY (id_promotion)');
    }
}
