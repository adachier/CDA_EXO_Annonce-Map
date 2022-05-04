<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504072225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, marque_id INT DEFAULT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, model VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, year INT NOT NULL, km DOUBLE PRECISION NOT NULL, fuel VARCHAR(255) NOT NULL, license TINYINT(1) NOT NULL, imgfile VARCHAR(255) NOT NULL, INDEX IDX_F65593E54827B9B2 (marque_id), INDEX IDX_F65593E5F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_list_by_user (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, annonces_id INT DEFAULT NULL, INDEX IDX_81A4A15E67B3B43D (users_id), INDEX IDX_81A4A15E4C2885D7 (annonces_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E54827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonce_list_by_user ADD CONSTRAINT FK_81A4A15E67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonce_list_by_user ADD CONSTRAINT FK_81A4A15E4C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonce (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_list_by_user DROP FOREIGN KEY FK_81A4A15E4C2885D7');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E54827B9B2');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F675F31B');
        $this->addSql('ALTER TABLE annonce_list_by_user DROP FOREIGN KEY FK_81A4A15E67B3B43D');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_list_by_user');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE user');
    }
}
