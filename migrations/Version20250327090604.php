<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327090604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD adresse_id INT NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD secteur VARCHAR(255) NOT NULL, ADD active TINYINT(1) NOT NULL, ADD telephone VARCHAR(20) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA604DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_D19FA604DE7DC5C ON entreprise (adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA604DE7DC5C');
        $this->addSql('DROP INDEX IDX_D19FA604DE7DC5C ON entreprise');
        $this->addSql('ALTER TABLE entreprise DROP adresse_id, DROP nom, DROP secteur, DROP active, DROP telephone, DROP email, DROP description');
    }
}
