<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825110526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket AS SELECT id, created_at, updated_at, deletedAt FROM basket');
        $this->addSql('DROP TABLE basket');
        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , deletedAt DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO basket (id, created_at, updated_at, deletedAt) SELECT id, created_at, updated_at, deletedAt FROM __temp__basket');
        $this->addSql('DROP TABLE __temp__basket');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, description, price, created_at, updated_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, price INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, description, price, created_at, updated_at) SELECT id, name, description, price, created_at, updated_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__basket AS SELECT id, created_at, updated_at, deletedAt FROM basket');
        $this->addSql('DROP TABLE basket');
        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deletedAt DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO basket (id, created_at, updated_at, deletedAt) SELECT id, created_at, updated_at, deletedAt FROM __temp__basket');
        $this->addSql('DROP TABLE __temp__basket');
        $this->addSql('ALTER TABLE product ADD COLUMN deletedAt DATETIME DEFAULT NULL');
    }
}
