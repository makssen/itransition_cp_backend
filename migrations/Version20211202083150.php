<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202083150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE overview (id INT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, tags TEXT NOT NULL, text TEXT NOT NULL, grade INT NOT NULL, images TEXT NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E7C3D1BB9D86650F ON overview (user_id_id)');
        $this->addSql('COMMENT ON COLUMN overview.tags IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN overview.images IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE overview ADD CONSTRAINT FK_E7C3D1BB9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE overview DROP CONSTRAINT FK_E7C3D1BB9D86650F');
        $this->addSql('DROP TABLE overview');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
    }
}
