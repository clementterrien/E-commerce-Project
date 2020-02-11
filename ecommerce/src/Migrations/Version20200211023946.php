<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211023946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year INT NOT NULL, type VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, grape VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, liter VARCHAR(255) NOT NULL, capacity INT NOT NULL, price INT DEFAULT NULL, like_counter INT DEFAULT NULL, order_counter INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_AACEE127A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, login_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, adress1 VARCHAR(255) NOT NULL, adress2 VARCHAR(255) DEFAULT NULL, postcode INT NOT NULL, city VARCHAR(255) NOT NULL, additional_info VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_5CECC7BEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_product (id INT AUTO_INCREMENT NOT NULL, favorite_list_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8E1EAAC360FAB8E5 (favorite_list_id), INDEX IDX_8E1EAAC34584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_list ADD CONSTRAINT FK_AACEE127A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_product ADD CONSTRAINT FK_8E1EAAC360FAB8E5 FOREIGN KEY (favorite_list_id) REFERENCES favorite_list (id)');
        $this->addSql('ALTER TABLE favorite_product ADD CONSTRAINT FK_8E1EAAC34584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE favorite_product DROP FOREIGN KEY FK_8E1EAAC34584665A');
        $this->addSql('ALTER TABLE favorite_product DROP FOREIGN KEY FK_8E1EAAC360FAB8E5');
        $this->addSql('ALTER TABLE favorite_list DROP FOREIGN KEY FK_AACEE127A76ED395');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BEA76ED395');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE favorite_list');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE favorite_product');
    }
}
