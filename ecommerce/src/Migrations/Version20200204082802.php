<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204082802 extends AbstractMigration
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
        $this->addSql('ALTER TABLE product ADD like_counter INT DEFAULT NULL, ADD order_counter INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalogue (name VARCHAR(106) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, year INT DEFAULT NULL, type VARCHAR(5) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, region VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, grape VARCHAR(26) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, designation VARCHAR(18) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, liter NUMERIC(4, 1) DEFAULT NULL, capacity INT DEFAULT NULL, price NUMERIC(5, 2) DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product DROP like_counter, DROP order_counter');
    }
}
