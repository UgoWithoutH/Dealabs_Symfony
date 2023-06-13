<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613214019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert_deal (alert_id INT NOT NULL, deal_id INT NOT NULL, INDEX IDX_6C4E139293035F72 (alert_id), INDEX IDX_6C4E1392F60E2305 (deal_id), PRIMARY KEY(alert_id, deal_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert_promo_code (alert_id INT NOT NULL, promo_code_id INT NOT NULL, INDEX IDX_BC83FB4E93035F72 (alert_id), INDEX IDX_BC83FB4E2FAE4625 (promo_code_id), PRIMARY KEY(alert_id, promo_code_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert_deal ADD CONSTRAINT FK_6C4E139293035F72 FOREIGN KEY (alert_id) REFERENCES alert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_deal ADD CONSTRAINT FK_6C4E1392F60E2305 FOREIGN KEY (deal_id) REFERENCES deal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_promo_code ADD CONSTRAINT FK_BC83FB4E93035F72 FOREIGN KEY (alert_id) REFERENCES alert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_promo_code ADD CONSTRAINT FK_BC83FB4E2FAE4625 FOREIGN KEY (promo_code_id) REFERENCES promo_code (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert_deal DROP FOREIGN KEY FK_6C4E139293035F72');
        $this->addSql('ALTER TABLE alert_deal DROP FOREIGN KEY FK_6C4E1392F60E2305');
        $this->addSql('ALTER TABLE alert_promo_code DROP FOREIGN KEY FK_BC83FB4E93035F72');
        $this->addSql('ALTER TABLE alert_promo_code DROP FOREIGN KEY FK_BC83FB4E2FAE4625');
        $this->addSql('DROP TABLE alert_deal');
        $this->addSql('DROP TABLE alert_promo_code');
    }
}
