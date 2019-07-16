<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624080153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE referentie (id INT AUTO_INCREMENT NOT NULL, domein VARCHAR(11) NOT NULL, code VARCHAR(11) NOT NULL, omschrijving VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sollicitatie (id INT AUTO_INCREMENT NOT NULL, vacature_id INT NOT NULL, user_id INT NOT NULL, datum DATETIME NOT NULL, uitgenodigd TINYINT(1) DEFAULT NULL, INDEX IDX_9577817D6FB89BA0 (vacature_id), INDEX IDX_9577817DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, plaats_id INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', voornaam VARCHAR(63) NOT NULL, achternaam VARCHAR(63) DEFAULT NULL, telefoonnummer VARCHAR(20) NOT NULL, adres VARCHAR(127) NOT NULL, postcode VARCHAR(8) NOT NULL, omschrijving VARCHAR(1023) NOT NULL, afbeelding VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), INDEX IDX_8D93D649935FAC7E (plaats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacature (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, niveau_id INT NOT NULL, plaats_id INT NOT NULL, titel VARCHAR(127) NOT NULL, datum DATETIME NOT NULL, icoon VARCHAR(255) DEFAULT NULL, omschrijving VARCHAR(1023) NOT NULL, INDEX IDX_9E5830F5A76ED395 (user_id), INDEX IDX_9E5830F5B3E9C81 (niveau_id), INDEX IDX_9E5830F5935FAC7E (plaats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sollicitatie ADD CONSTRAINT FK_9577817D6FB89BA0 FOREIGN KEY (vacature_id) REFERENCES vacature (id)');
        $this->addSql('ALTER TABLE sollicitatie ADD CONSTRAINT FK_9577817DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649935FAC7E FOREIGN KEY (plaats_id) REFERENCES referentie (id)');
        $this->addSql('ALTER TABLE vacature ADD CONSTRAINT FK_9E5830F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacature ADD CONSTRAINT FK_9E5830F5B3E9C81 FOREIGN KEY (niveau_id) REFERENCES referentie (id)');
        $this->addSql('ALTER TABLE vacature ADD CONSTRAINT FK_9E5830F5935FAC7E FOREIGN KEY (plaats_id) REFERENCES referentie (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649935FAC7E');
        $this->addSql('ALTER TABLE vacature DROP FOREIGN KEY FK_9E5830F5B3E9C81');
        $this->addSql('ALTER TABLE vacature DROP FOREIGN KEY FK_9E5830F5935FAC7E');
        $this->addSql('ALTER TABLE sollicitatie DROP FOREIGN KEY FK_9577817DA76ED395');
        $this->addSql('ALTER TABLE vacature DROP FOREIGN KEY FK_9E5830F5A76ED395');
        $this->addSql('ALTER TABLE sollicitatie DROP FOREIGN KEY FK_9577817D6FB89BA0');
        $this->addSql('DROP TABLE referentie');
        $this->addSql('DROP TABLE sollicitatie');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vacature');
    }
}
