<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717222924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
                id   INT AUTO_INCREMENT PRIMARY KEY,
                token VARCHAR(100) NOT NULL UNIQUE
            );
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
