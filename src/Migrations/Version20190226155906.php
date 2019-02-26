<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226155906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE painting_discount DROP FOREIGN KEY FK_9E7EAC819F442369');
        $this->addSql('ALTER TABLE painting_discount ADD CONSTRAINT FK_9E7EAC819F442369 FOREIGN KEY (painting_id_id) REFERENCES painting (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE painting_discount DROP FOREIGN KEY FK_9E7EAC819F442369');
        $this->addSql('ALTER TABLE painting_discount ADD CONSTRAINT FK_9E7EAC819F442369 FOREIGN KEY (painting_id_id) REFERENCES painting (id)');
    }
}
