<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307203940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE painting_discount');
        $this->addSql('ALTER TABLE painting ADD discount SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE painting_discount (id INT AUTO_INCREMENT NOT NULL, painting_id INT NOT NULL, discount SMALLINT NOT NULL, UNIQUE INDEX UNIQ_9E7EAC81B00EB939 (painting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE painting_discount ADD CONSTRAINT FK_9E7EAC81B00EB939 FOREIGN KEY (painting_id) REFERENCES painting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE painting DROP discount');
    }
}
