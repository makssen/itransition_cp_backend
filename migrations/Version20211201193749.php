<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201193749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE like_id_seq CASCADE');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('ALTER TABLE overview DROP CONSTRAINT fk_e7c3d1bb67b3b43d');
        $this->addSql('DROP INDEX idx_e7c3d1bb67b3b43d');
        $this->addSql('ALTER TABLE overview RENAME COLUMN users_id TO user_id_id');
        $this->addSql('ALTER TABLE overview ADD CONSTRAINT FK_E7C3D1BB9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E7C3D1BB9D86650F ON overview (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "like" (id INT NOT NULL, overview_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ac6340b33504b372 ON "like" (overview_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_ac6340b367b3b43d ON "like" (users_id)');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT fk_ac6340b33504b372 FOREIGN KEY (overview_id) REFERENCES overview (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT fk_ac6340b367b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE overview DROP CONSTRAINT FK_E7C3D1BB9D86650F');
        $this->addSql('DROP INDEX IDX_E7C3D1BB9D86650F');
        $this->addSql('ALTER TABLE overview RENAME COLUMN user_id_id TO users_id');
        $this->addSql('ALTER TABLE overview ADD CONSTRAINT fk_e7c3d1bb67b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e7c3d1bb67b3b43d ON overview (users_id)');
    }
}
