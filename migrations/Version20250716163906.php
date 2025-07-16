<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250716163906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Vehicle Database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE manufacturers (
                id   INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE
            );
        ');

        $this->addSql('
            CREATE TABLE vehicle_types (
                id        INT AUTO_INCREMENT PRIMARY KEY,
                type_name VARCHAR(50) NOT NULL
            );
        ');

        $this->addSql('
            CREATE TABLE vehicles (
                id              INT AUTO_INCREMENT PRIMARY KEY,
                name            VARCHAR(100) NOT NULL,
                manufacturer_id INT          NOT NULL,
                vehicle_type_id INT          NOT NULL,
                FOREIGN KEY (manufacturer_id) REFERENCES manufacturers (id) ON DELETE CASCADE,
                FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types (id) ON DELETE CASCADE
            );
        ');

        $this->addSql("
            CREATE TABLE vehicle_specs (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                vehicle_id  INT NOT NULL,
                engine_type ENUM ('Electric', 'Diesel', 'Petrol', 'Hybrid'),
                horsepower  INT DEFAULT NULL,
                top_speed   INT DEFAULT NULL,
                price       INT DEFAULT NULL,
                length      INT DEFAULT NULL,
                width       INT DEFAULT NULL,
                drive_type  ENUM ('RWD', 'FWD', 'AWD', '4WD'),
                FOREIGN KEY (vehicle_id) REFERENCES vehicles (id) ON DELETE CASCADE
            );
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE vehicle_specs');
        $this->addSql('DROP TABLE vehicles');
        $this->addSql('DROP TABLE vehicle_types');
        $this->addSql('DROP TABLE manufacturers');
    }
}
