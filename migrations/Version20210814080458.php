<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210814080458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, wine_id INT NOT NULL, name VARCHAR(255) NOT NULL, alcool INT NOT NULL, color VARCHAR(255) NOT NULL, vintage INT DEFAULT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, capacity DOUBLE PRECISION NOT NULL, designation_of_origin VARCHAR(255) DEFAULT NULL, grape_variety VARCHAR(255) DEFAULT NULL, tastes VARCHAR(255) DEFAULT NULL, smell VARCHAR(255) DEFAULT NULL, service_temperature VARCHAR(255) DEFAULT NULL, to_drink_until INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wine');
    }
}
