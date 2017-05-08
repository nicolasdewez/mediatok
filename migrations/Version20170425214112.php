<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170425214112 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('
            INSERT INTO field (id, title, active)  VALUES
            (nextval(\'field_id_seq\'), \'Genre\', true),
            (nextval(\'field_id_seq\'), \'Date de parution\', true),
            (nextval(\'field_id_seq\'), \'Auteur\', true),
            (nextval(\'field_id_seq\'), \'Editeur\', true),
            (nextval(\'field_id_seq\'), \'Collection\', true),
            (nextval(\'field_id_seq\'), \'Nombre de pages\', true),
            (nextval(\'field_id_seq\'), \'Date de sortie\', true),
            (nextval(\'field_id_seq\'), \'Réalisateur\', true),
            (nextval(\'field_id_seq\'), \'Acteurs\', true),
            (nextval(\'field_id_seq\'), \'Jeu en réseau\', true),
            (nextval(\'field_id_seq\'), \'Nombre de joueurs\', true),
            (nextval(\'field_id_seq\'), \'Interprête\', true),
            (nextval(\'field_id_seq\'), \'Compositeur\', true)            
            ;
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM field');
    }
}
