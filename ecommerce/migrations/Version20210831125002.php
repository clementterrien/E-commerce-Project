<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831125002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wine_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine_category_wine (wine_category_id INT NOT NULL, wine_id INT NOT NULL, INDEX IDX_9608253099DC0822 (wine_category_id), INDEX IDX_9608253028A2BD76 (wine_id), PRIMARY KEY(wine_category_id, wine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wine_category_wine ADD CONSTRAINT FK_9608253099DC0822 FOREIGN KEY (wine_category_id) REFERENCES wine_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wine_category_wine ADD CONSTRAINT FK_9608253028A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wine_category_wine DROP FOREIGN KEY FK_9608253099DC0822');
        $this->addSql('DROP TABLE wine_category');
        $this->addSql('DROP TABLE wine_category_wine');
    }
}
