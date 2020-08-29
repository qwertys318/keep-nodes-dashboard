<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828180201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE node (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, address VARCHAR(42) NOT NULL, unbonded_value NUMERIC(32, 18) NOT NULL, image CLOB NOT NULL, created_ts DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_857FE845D4E6F81 ON node (address)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE node');
    }
}
