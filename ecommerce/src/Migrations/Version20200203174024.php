<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203174024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year INT NOT NULL, type VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, grape VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, liter VARCHAR(255) NOT NULL, capacity INT NOT NULL, price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_product (id INT AUTO_INCREMENT NOT NULL, favorite_list_id INT NOT NULL, INDEX IDX_8E1EAAC360FAB8E5 (favorite_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_product ADD CONSTRAINT FK_8E1EAAC360FAB8E5 FOREIGN KEY (favorite_list_id) REFERENCES favorite_list (id)');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE favorite_article');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, year INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, region VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, grape VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, designation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, liter INT DEFAULT NULL, price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_article (id INT AUTO_INCREMENT NOT NULL, favorite_list_id INT NOT NULL, INDEX IDX_5F6EA00860FAB8E5 (favorite_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite_article ADD CONSTRAINT FK_5F6EA00860FAB8E5 FOREIGN KEY (favorite_list_id) REFERENCES favorite_list (id)');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE favorite_product');
    }
}
