<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320145000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create entreprise table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE entreprise (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            secteur VARCHAR(255) NOT NULL,
            adresse_id INT NOT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            telephone VARCHAR(20) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            description LONGTEXT DEFAULT NULL,
            INDEX IDX_D19FA60A4DE7DC5C (adresse_id),
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60A4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60A4DE7DC5C');
        $this->addSql('DROP TABLE entreprise');
    }
} 