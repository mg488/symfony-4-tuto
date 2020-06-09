<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609125356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Tabapplication (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, author VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date_crea DATETIME NOT NULL, date_maj DATETIME DEFAULT NULL, INDEX IDX_37CCA020D07ECCB6 (advert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Tabapplication ADD CONSTRAINT FK_37CCA020D07ECCB6 FOREIGN KEY (advert_id) REFERENCES Tabadvert (id)');
        $this->addSql('ALTER TABLE tabadvert CHANGE image_id image_id INT DEFAULT NULL, CHANGE date_maj date_maj DATETIME DEFAULT NULL, CHANGE published published TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Tabapplication');
        $this->addSql('ALTER TABLE Tabadvert CHANGE image_id image_id INT DEFAULT NULL, CHANGE date_maj date_maj DATETIME DEFAULT \'NULL\', CHANGE published published TINYINT(1) DEFAULT \'NULL\'');
    }
}
