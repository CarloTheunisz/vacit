<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628071138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE plaats_id plaats_id INT DEFAULT NULL, CHANGE voornaam voornaam VARCHAR(63) DEFAULT NULL, CHANGE telefoonnummer telefoonnummer VARCHAR(20) DEFAULT NULL, CHANGE adres adres VARCHAR(127) DEFAULT NULL, CHANGE postcode postcode VARCHAR(8) DEFAULT NULL, CHANGE omschrijving omschrijving VARCHAR(1023) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE plaats_id plaats_id INT NOT NULL, CHANGE voornaam voornaam VARCHAR(63) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE telefoonnummer telefoonnummer VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE adres adres VARCHAR(127) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE postcode postcode VARCHAR(8) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE omschrijving omschrijving VARCHAR(1023) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
