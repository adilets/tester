<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171220155402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type ENUM(\'compiler\', \'interpreter\')');
        $this->addSql('ALTER TABLE solution CHANGE user_id user_id INT DEFAULT NULL, CHANGE problem_id problem_id INT DEFAULT NULL, CHANGE language_id language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBA0DCED86 FOREIGN KEY (problem_id) REFERENCES problem (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_9F3329DBA76ED395 ON solution (user_id)');
        $this->addSql('CREATE INDEX IDX_9F3329DBA0DCED86 ON solution (problem_id)');
        $this->addSql('CREATE INDEX IDX_9F3329DB82F1BAF4 ON solution (language_id)');
        $this->addSql('CREATE INDEX IDX_9F3329DB33D1A3E7 ON solution (tournament_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBA76ED395');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBA0DCED86');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB82F1BAF4');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB33D1A3E7');
        $this->addSql('DROP INDEX IDX_9F3329DBA76ED395 ON solution');
        $this->addSql('DROP INDEX IDX_9F3329DBA0DCED86 ON solution');
        $this->addSql('DROP INDEX IDX_9F3329DB82F1BAF4 ON solution');
        $this->addSql('DROP INDEX IDX_9F3329DB33D1A3E7 ON solution');
        $this->addSql('ALTER TABLE solution CHANGE user_id user_id INT NOT NULL, CHANGE problem_id problem_id INT NOT NULL, CHANGE language_id language_id INT NOT NULL');
    }
}
