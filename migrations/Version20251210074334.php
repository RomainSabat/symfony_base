<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210074334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, author_id INTEGER DEFAULT NULL, CONSTRAINT FK_527EDB25F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_527EDB25F675F31B ON task (author_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__candidate AS SELECT id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at FROM candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('CREATE TABLE candidate (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, has_experience BOOLEAN NOT NULL, experience_details VARCHAR(255) DEFAULT NULL, availability_date DATE DEFAULT NULL, status VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, available_now BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO candidate (id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at) SELECT id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at FROM __temp__candidate');
        $this->addSql('DROP TABLE __temp__candidate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__candidate AS SELECT id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at FROM candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('CREATE TABLE candidate (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, has_experience BOOLEAN NOT NULL, experience_details VARCHAR(255) DEFAULT NULL, availability_date DATE NOT NULL, status VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO candidate (id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at) SELECT id, firstname, lastname, email, phone, has_experience, experience_details, availability_date, status, create_at, updated_at FROM __temp__candidate');
        $this->addSql('DROP TABLE __temp__candidate');
    }
}
