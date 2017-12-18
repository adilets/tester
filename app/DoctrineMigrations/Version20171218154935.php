<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171218154935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type ENUM(\'compiler\', \'interpreter\')');
        $this->addSql('ALTER TABLE solution CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_9F3329DB6BF700BD ON solution (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB6BF700BD');
        $this->addSql('DROP INDEX IDX_9F3329DB6BF700BD ON solution');
        $this->addSql('ALTER TABLE solution CHANGE status_id status_id INT NOT NULL');
    }
}
