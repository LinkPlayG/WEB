<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320144950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD pilote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1F510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilote_de_promotion (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1F510AAE9 ON promotion (pilote_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1F510AAE9');
        $this->addSql('DROP INDEX IDX_C11D7DD1F510AAE9 ON promotion');
        $this->addSql('ALTER TABLE promotion DROP pilote_id');
    }
}
