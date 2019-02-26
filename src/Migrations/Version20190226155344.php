<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226155344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, commentary LONGTEXT DEFAULT NULL, date_of_birth DATE DEFAULT NULL, date_of_death DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE painting (id INT AUTO_INCREMENT NOT NULL, artist_id INT DEFAULT NULL, media_id INT DEFAULT NULL, style_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, dimensions VARCHAR(100) DEFAULT NULL, year DATE DEFAULT NULL, descripition LONGTEXT DEFAULT NULL, price INT NOT NULL, INDEX IDX_66B9EBA0B7970CF8 (artist_id), INDEX IDX_66B9EBA0EA9FDD75 (media_id), INDEX IDX_66B9EBA0BACD6074 (style_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE painting_discount (id INT AUTO_INCREMENT NOT NULL, painting_id_id INT NOT NULL, discount SMALLINT NOT NULL, UNIQUE INDEX UNIQ_9E7EAC819F442369 (painting_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE painting_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE painting_style (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE painting ADD CONSTRAINT FK_66B9EBA0B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE painting ADD CONSTRAINT FK_66B9EBA0EA9FDD75 FOREIGN KEY (media_id) REFERENCES painting_media (id)');
        $this->addSql('ALTER TABLE painting ADD CONSTRAINT FK_66B9EBA0BACD6074 FOREIGN KEY (style_id) REFERENCES painting_style (id)');
        $this->addSql('ALTER TABLE painting_discount ADD CONSTRAINT FK_9E7EAC819F442369 FOREIGN KEY (painting_id_id) REFERENCES painting (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE painting DROP FOREIGN KEY FK_66B9EBA0B7970CF8');
        $this->addSql('ALTER TABLE painting_discount DROP FOREIGN KEY FK_9E7EAC819F442369');
        $this->addSql('ALTER TABLE painting DROP FOREIGN KEY FK_66B9EBA0EA9FDD75');
        $this->addSql('ALTER TABLE painting DROP FOREIGN KEY FK_66B9EBA0BACD6074');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE painting');
        $this->addSql('DROP TABLE painting_discount');
        $this->addSql('DROP TABLE painting_media');
        $this->addSql('DROP TABLE painting_style');
    }
}
