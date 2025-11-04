<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104154754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feedback (id SERIAL NOT NULL, matricule_concerned INT NOT NULL, matricule_insert INT NOT NULL, date_inserted TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, critere_1 INT NOT NULL, critere_2 INT NOT NULL, critere_3 INT NOT NULL, critere_4 INT NOT NULL, critere_5 INT NOT NULL, commentary VARCHAR(255) NOT NULL, type_feedback INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE feedback');
    }
}
