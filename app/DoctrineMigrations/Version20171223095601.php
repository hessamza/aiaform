<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171223095601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blocked_ips CHANGE type type enum(\'login\', \'forgot_password\')');
        $this->addSql('ALTER TABLE contract CHANGE recharge recharge enum(\'lastMonth\',\'ago\'), CHANGE register register enum(\'lastMonth\',\'ago\'), CHANGE phone phone enum(\'lastMonth\',\'ago\'), CHANGE telegram telegram enum(\'lastMonth\',\'ago\',\'advTelegram\'), CHANGE separate separate enum(\'global\',\'local\',\'professional\',\'local-professional\'), CHANGE contract_date contract_date DATETIME DEFAULT NULL, CHANGE contract_type contract_type enum(\'recharge\',\'register\',\'phone\',\'telegram\',\'direct\',\'exhibition\',\'adv\'), CHANGE contract_time contract_time enum(\'6month\',\'12month\'), CHANGE contract_start_date contract_start_date DATETIME DEFAULT NULL, CHANGE contract_end_date contract_end_date DATETIME DEFAULT NULL, CHANGE exhibition exhibition enum(\'oil96\'), CHANGE adv adv enum(\'site\',\'email\',\'telegram\',\'sms\'), CHANGE item_send item_send TINYINT(1) NOT NULL, CHANGE accept accept TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blocked_ips CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE contract CHANGE contract_date contract_date DATETIME DEFAULT NULL, CHANGE contract_start_date contract_start_date DATETIME DEFAULT NULL, CHANGE contract_end_date contract_end_date DATETIME DEFAULT NULL, CHANGE contract_type contract_type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE contract_time contract_time VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE recharge recharge VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE register register VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE phone phone VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE telegram telegram VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE exhibition exhibition VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE adv adv VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE separate separate VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE item_send item_send TINYINT(1) DEFAULT NULL, CHANGE accept accept TINYINT(1) DEFAULT NULL');
    }
}
