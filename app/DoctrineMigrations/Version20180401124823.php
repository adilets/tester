<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180401124823 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_university (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE language CHANGE type type ENUM(\'compiler\', \'interpreter\')');
        $this->addSql('ALTER TABLE fos_group ADD university_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_group ADD CONSTRAINT FK_4B019DDB309D1878 FOREIGN KEY (university_id) REFERENCES fos_university (id)');
        $this->addSql('CREATE INDEX IDX_4B019DDB309D1878 ON fos_group (university_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_group DROP FOREIGN KEY FK_4B019DDB309D1878');
        $this->addSql('DROP TABLE fos_university');
        $this->addSql('DROP INDEX IDX_4B019DDB309D1878 ON fos_group');
        $this->addSql('ALTER TABLE fos_group DROP university_id');
        $this->addSql('ALTER TABLE language CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
