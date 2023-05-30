<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530064150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal CHANGE `group` group_deal VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE promo_code CHANGE `group` group_deal VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code CHANGE group_deal `group` VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE deal CHANGE group_deal `group` VARCHAR(20) NOT NULL');
    }
}
