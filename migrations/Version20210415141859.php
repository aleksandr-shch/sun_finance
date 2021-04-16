<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415141859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applications (id INT NOT NULL, client_id INT DEFAULT NULL, term INT NOT NULL, amount NUMERIC(6, 2) NOT NULL, currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX application_idx ON applications (client_id)');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX client_idx ON clients (email)');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F019EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE applications DROP CONSTRAINT FK_F7C966F019EB6921');
        $this->addSql('DROP TABLE applications');
        $this->addSql('DROP TABLE clients');
    }
}
