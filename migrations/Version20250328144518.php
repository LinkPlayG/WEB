<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328144518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add geolocation fields to adresse table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adresse ADD latitude DOUBLE PRECISION DEFAULT NULL, ADD longitude DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adresse DROP latitude, DROP longitude');
    }
}
