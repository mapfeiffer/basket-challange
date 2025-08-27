<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250827095046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE basket_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, basket_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_D4943C2B4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D4943C2B1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D4943C2B4584665A ON basket_item (product_id)');
        $this->addSql('CREATE INDEX IDX_D4943C2B1BE1FB52 ON basket_item (basket_id)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, price INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE basket_item');
        $this->addSql('DROP TABLE product');
    }
}
