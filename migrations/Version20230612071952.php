<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612071952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, deal_id INT DEFAULT NULL, promo_code_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, datetime DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CF60E2305 (deal_id), INDEX IDX_9474526C2FAE4625 (promo_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, hot_level INT DEFAULT 0, publication_datetime DATETIME NOT NULL, expiration_datetime DATETIME NOT NULL, link VARCHAR(255) DEFAULT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, promo_code VARCHAR(50) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, usual_price DOUBLE PRECISION DEFAULT NULL, shipping_cost DOUBLE PRECISION DEFAULT NULL, free_delivery TINYINT(1) NOT NULL, group_deal VARCHAR(20) NOT NULL, INDEX IDX_E3FEC116F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_code (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, hot_level INT DEFAULT 0, publication_datetime DATETIME NOT NULL, expiration_datetime DATETIME NOT NULL, link VARCHAR(255) DEFAULT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, promo_code_value VARCHAR(50) DEFAULT NULL, group_deal VARCHAR(20) NOT NULL, type_of_reduction VARCHAR(20) DEFAULT NULL, INDEX IDX_3D8C939EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(30) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, deals_and_promocodes_save VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF60E2305 FOREIGN KEY (deal_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2FAE4625 FOREIGN KEY (promo_code_id) REFERENCES promo_code (id)');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC116F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promo_code ADD CONSTRAINT FK_3D8C939EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF60E2305');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2FAE4625');
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC116F675F31B');
        $this->addSql('ALTER TABLE promo_code DROP FOREIGN KEY FK_3D8C939EF675F31B');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE deal');
        $this->addSql('DROP TABLE promo_code');
        $this->addSql('DROP TABLE user');
    }
}
