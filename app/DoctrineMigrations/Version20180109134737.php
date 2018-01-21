<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180109134737 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type ENUM(\'compiler\', \'interpreter\')');
        $this->addSql('ALTER TABLE test_result CHANGE solution_id solution_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE memory memory INT DEFAULT NULL');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D1C0BE183 FOREIGN KEY (solution_id) REFERENCES solution (id)');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_84B3C63D1C0BE183 ON test_result (solution_id)');
        $this->addSql('CREATE INDEX IDX_84B3C63D6BF700BD ON test_result (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63D1C0BE183');
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63D6BF700BD');
        $this->addSql('DROP INDEX IDX_84B3C63D1C0BE183 ON test_result');
        $this->addSql('DROP INDEX IDX_84B3C63D6BF700BD ON test_result');
        $this->addSql('ALTER TABLE test_result CHANGE solution_id solution_id INT NOT NULL, CHANGE status_id status_id INT NOT NULL, CHANGE memory memory VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
