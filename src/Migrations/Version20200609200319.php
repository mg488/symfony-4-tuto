<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609200319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE advert_category');
        $this->addSql('DROP TABLE tabimage');
        $this->addSql('ALTER TABLE tabadvert CHANGE image_id image_id INT DEFAULT NULL, CHANGE date_maj date_maj DATETIME DEFAULT NULL, CHANGE published published TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE tabapplication CHANGE date_maj date_maj DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE advert_category (advert_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_84EEA34012469DE2 (category_id), INDEX IDX_84EEA340D07ECCB6 (advert_id), PRIMARY KEY(advert_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tabimage (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, alt VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA34012469DE2 FOREIGN KEY (category_id) REFERENCES tabcategory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA340D07ECCB6 FOREIGN KEY (advert_id) REFERENCES tabadvert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Tabadvert CHANGE image_id image_id INT DEFAULT NULL, CHANGE date_maj date_maj DATETIME DEFAULT \'NULL\', CHANGE published published TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE Tabapplication CHANGE date_maj date_maj DATETIME DEFAULT \'NULL\'');
    }
}
