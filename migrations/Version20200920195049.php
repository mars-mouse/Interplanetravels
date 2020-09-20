<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920195049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE travel_date (id INT AUTO_INCREMENT NOT NULL, date_departure DATETIME NOT NULL, date_return DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary CHANGE day_arrival day_arrival INT NOT NULL, CHANGE day_departure day_departure INT NOT NULL');
        $this->addSql('ALTER TABLE travel DROP date_departure, DROP date_return');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE travel_date');
        $this->addSql('ALTER TABLE itinerary CHANGE day_arrival day_arrival DATETIME NOT NULL, CHANGE day_departure day_departure DATETIME NOT NULL');
        $this->addSql('ALTER TABLE travel ADD date_departure DATETIME DEFAULT NULL, ADD date_return DATETIME DEFAULT NULL');
    }
}
