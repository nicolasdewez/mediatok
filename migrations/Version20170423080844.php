<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170423080844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE field_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE format_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE field (id SMALLINT NOT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media (id SMALLINT NOT NULL, type_id SMALLINT DEFAULT NULL, format_id SMALLINT DEFAULT NULL, title VARCHAR(255) NOT NULL, fields JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10CC54C8C93 ON media (type_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CD629F605 ON media (format_id)');
        $this->addSql('CREATE TABLE type (id SMALLINT NOT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8CDE57292B36786B ON type (title)');
        $this->addSql('CREATE TABLE type_field (type_id SMALLINT NOT NULL, field_id SMALLINT NOT NULL, PRIMARY KEY(type_id, field_id))');
        $this->addSql('CREATE INDEX IDX_55F9C2AFC54C8C93 ON type_field (type_id)');
        $this->addSql('CREATE INDEX IDX_55F9C2AF443707B0 ON type_field (field_id)');
        $this->addSql('CREATE TABLE format (id SMALLINT NOT NULL, type_id SMALLINT DEFAULT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEBA72DFC54C8C93 ON format (type_id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CD629F605 FOREIGN KEY (format_id) REFERENCES format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_field ADD CONSTRAINT FK_55F9C2AFC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_field ADD CONSTRAINT FK_55F9C2AF443707B0 FOREIGN KEY (field_id) REFERENCES field (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE format ADD CONSTRAINT FK_DEBA72DFC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE type_field DROP CONSTRAINT FK_55F9C2AF443707B0');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CC54C8C93');
        $this->addSql('ALTER TABLE type_field DROP CONSTRAINT FK_55F9C2AFC54C8C93');
        $this->addSql('ALTER TABLE format DROP CONSTRAINT FK_DEBA72DFC54C8C93');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CD629F605');
        $this->addSql('DROP SEQUENCE field_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE format_id_seq CASCADE');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_field');
        $this->addSql('DROP TABLE format');
    }
}
