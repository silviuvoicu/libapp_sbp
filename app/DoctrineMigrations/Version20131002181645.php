<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131002181645 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE books ADD reader_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE books ADD CONSTRAINT FK_4A1B2A921717D737 FOREIGN KEY (reader_id) REFERENCES readers (id)");
        $this->addSql("CREATE INDEX IDX_4A1B2A921717D737 ON books (reader_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A921717D737");
        $this->addSql("DROP INDEX IDX_4A1B2A921717D737 ON books");
        $this->addSql("ALTER TABLE books DROP reader_id");
    }
}
