<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180401125233 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('ALTER TABLE fos_user ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)');
        $this->addSql('CREATE INDEX IDX_957A6479FE54D947 ON fos_user (group_id)');
        $this->addSql('ALTER TABLE language CHANGE type type ENUM(\'compiler\', \'interpreter\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479FE54D947');
        $this->addSql('DROP INDEX IDX_957A6479FE54D947 ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP group_id');
        $this->addSql('ALTER TABLE language CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
