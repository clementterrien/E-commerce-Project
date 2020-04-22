<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200420153838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE catalogue');
        $this->addSql('ALTER TABLE product CHANGE enabled enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalogue (region VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, designation VARCHAR(35) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, grape VARCHAR(38) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, name VARCHAR(108) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, type VARCHAR(9) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, year INT DEFAULT NULL, liter NUMERIC(4, 1) DEFAULT NULL, Stock INT DEFAULT NULL, price NUMERIC(7, 2) DEFAULT NULL, alcool NUMERIC(3, 1) DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product CHANGE enabled enabled TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
