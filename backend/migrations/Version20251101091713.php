<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101091713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD statut INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD date_creation DATE NOT NULL');
        $this->addSql('ALTER TABLE users ADD last_connexion DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP firstname');
        $this->addSql('ALTER TABLE users DROP statut');
        $this->addSql('ALTER TABLE users DROP date_creation');
        $this->addSql('ALTER TABLE users DROP last_connexion');
    }
}
