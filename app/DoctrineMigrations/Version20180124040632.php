<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180124040632 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE solution CHANGE memory memory INT NOT NULL');
        $this->addSql('ALTER TABLE language ADD extension VARCHAR(20) NOT NULL, CHANGE type type ENUM(\'compiler\', \'interpreter\')');
        $this->addSql('ALTER TABLE test_result CHANGE memory memory INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language DROP extension, CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE solution CHANGE memory memory INT DEFAULT 0');
        $this->addSql('ALTER TABLE test_result CHANGE memory memory INT DEFAULT NULL');
    }
}
