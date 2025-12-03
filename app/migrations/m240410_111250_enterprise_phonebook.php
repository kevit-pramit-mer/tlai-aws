<?php

use yii\db\Migration;

/**
 * Class m240410_111250_enterprise_phonebook
 */
class m240410_111250_enterprise_phonebook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `tbl_enterprise_phonebook` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `en_extension` int(11) DEFAULT NULL COMMENT 'Extension',
            `en_first_name` varchar(50) DEFAULT NULL COMMENT 'First Name',
            `en_last_name` varchar(50) DEFAULT NULL COMMENT 'Last Name',
            `en_mobile` varchar(15) DEFAULT NULL,
            `en_phone` varchar(15) DEFAULT NULL,
            `en_email_id` varchar(211) DEFAULT NULL,
            `en_status` enum('0','1') NOT NULL DEFAULT '1',
            `trago_ed_id` int(11) DEFAULT NULL
            );
        ");

        $this->execute("
            INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
            ('/enterprisePhonebook/enterprise-phonebook/index', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/view', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/export', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/import', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/download-sample-file', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/create', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/update', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/delete', 2, NULL, NULL, NULL, 1563449097, 1563449097),
            ('/enterprisePhonebook/enterprise-phonebook/*', 2, NULL, NULL, NULL, 1581486399, 1581486399),
            ('/enterprisePhonebook/*', 2, NULL, NULL, NULL, 1581486399, 1581486399);
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES 
                ('super_admin', '/enterprisePhonebook/*'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/*'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/index'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/view'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/export'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/import'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/download-sample-file'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/create'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/update'),
                ('super_admin', '/enterprisePhonebook/enterprise-phonebook/delete');");

        $this->execute("INSERT INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES (NULL, 'enterprisePhonebook/enterprise-phonebook', 'Enterprise Phonebook', 'Y', 'Y', 'Y', '7');");

        $this->execute("Update `page_access` SET `priority` = 1 where `page_desc` = 'Real Time SIP Extension Registration Status Report';
            Update `page_access` SET `priority` = 2 where `page_desc` = 'Real Time Agent Monitor Report';
            Update `page_access` SET `priority` = 3 where `page_desc` = 'Real Time Queue Status Report';
            Update `page_access` SET `priority` = 4 where `page_desc` = 'Real Time Active Calls Report';
            Update `page_access` SET `priority` = 5 where `page_desc` = 'Real Time Campaign Performance Report';
            Update `page_access` SET `priority` = 6 where `page_desc` = 'Extensions';
            Update `page_access` SET `priority` = 7 where `page_desc` = 'Enterprise Phonebook';
            Update `page_access` SET `priority` = 8 where `page_desc` = 'Feature Codes';
            Update `page_access` SET `priority` = 9 where `page_desc` = 'Conferences';
            Update `page_access` SET `priority` = 10 where `page_desc` = 'Ring Group';
            Update `page_access` SET `priority` = 11 where `page_desc` = 'Groups';
            Update `page_access` SET `priority` = 12 where `page_desc` = 'Fax';
            Update `page_access` SET `priority` = 13 where `page_desc` = 'Parking Lot';
            Update `page_access` SET `priority` = 14 where `page_desc` = 'Trunks';
            Update `page_access` SET `priority` = 15 where `page_desc` = 'Trunk Groups';
            Update `page_access` SET `priority` = 16 where `page_desc` = 'Outbound Dial Plans';
            Update `page_access` SET `priority` = 17 where `page_desc` = 'DID Management';
            Update `page_access` SET `priority` = 18 where `page_desc` = 'Audio Libraries';
            Update `page_access` SET `priority` = 19 where `page_desc` = 'IVR';
            Update `page_access` SET `priority` = 20 where `page_desc` = 'Holidays';
            Update `page_access` SET `priority` = 21 where `page_desc` = 'Week Offs';
            Update `page_access` SET `priority` = 22 where `page_desc` = 'Queues';
            Update `page_access` SET `priority` = 23 where `page_desc` = 'Campaign Management';
            Update `page_access` SET `priority` = 24 where `page_desc` = 'Disposition';
            Update `page_access` SET `priority` = 25 where `page_desc` = 'Disposition Status';
            Update `page_access` SET `priority` = 26 where `page_desc` = 'Lead Group';
            Update `page_access` SET `priority` = 27 where `page_desc` = 'Lead Group Member';
            Update `page_access` SET `priority` = 28 where `page_desc` = 'Script';
            Update `page_access` SET `priority` = 29 where `page_desc` = 'Redial Call';
            Update `page_access` SET `priority` = 30 where `page_desc` = 'Shifts';
            Update `page_access` SET `priority` = 31 where `page_desc` = 'Break Management';
            Update `page_access` SET `priority` = 32 where `page_desc` = 'Users';
            Update `page_access` SET `priority` = 33 where `page_desc` = 'Roles';
            Update `page_access` SET `priority` = 34 where `page_desc` = 'Agents';
            Update `page_access` SET `priority` = 35 where `page_desc` = 'Supervisor ';
            Update `page_access` SET `priority` = 36 where `page_desc` = 'Fraud Call Detection';
            Update `page_access` SET `priority` = 37 where `page_desc` = 'Access Restriction';
            Update `page_access` SET `priority` = 38 where `page_desc` = 'White List';
            Update `page_access` SET `priority` = 39 where `page_desc` = 'Black List';
            Update `page_access` SET `priority` = 40 where `page_desc` = 'Fail2ban ';
            Update `page_access` SET `priority` = 41 where `page_desc` = 'IP Table';
            Update `page_access` SET `priority` = 42 where `page_desc` = 'Log Viewer';
            Update `page_access` SET `priority` = 43 where `page_desc` = 'PCap Management';
            Update `page_access` SET `priority` = 44 where `page_desc` = 'Call Detail Records';
            Update `page_access` SET `priority` = 45 where `page_desc` = 'Extension Summary Report';
            Update `page_access` SET `priority` = 46 where `page_desc` = 'Fax Details Report';
            Update `page_access` SET `priority` = 47 where `page_desc` = 'Fraudcall Detection Report';
            Update `page_access` SET `priority` = 48 where `page_desc` = 'Blacklist Number Details';
            Update `page_access` SET `priority` = 49 where `page_desc` = 'Abandoned Call Report';
            Update `page_access` SET `priority` = 50 where `page_desc` = 'Queue Wise Report';
            Update `page_access` SET `priority` = 51 where `page_desc` = 'Call Time Distribution';
            Update `page_access` SET `priority` = 52 where `page_desc` = 'Agents Wise Report';
            Update `page_access` SET `priority` = 53 where `page_desc` = 'Agent Performance Report';
            Update `page_access` SET `priority` = 54 where `page_desc` = 'Time Clock Report';
            Update `page_access` SET `priority` = 55 where `page_desc` = 'Campaign Summary Report';
            Update `page_access` SET `priority` = 56 where `page_desc` = 'Disposition Report';
            Update `page_access` SET `priority` = 57 where `page_desc` = 'Hourly Call Report';
            Update `page_access` SET `priority` = 58 where `page_desc` = 'Lead Performance Report';
            Update `page_access` SET `priority` = 59 where `page_desc` = 'Global Configurations';
            Update `page_access` SET `priority` = 60 where `page_desc` = 'Queue Call Back';
            Update `page_access` SET `priority` = 61 where `page_desc` = 'DB Backup ';
            Update `page_access` SET `priority` = 62 where `page_desc` = 'Time Conditions';
            Update `page_access` SET `priority` = 63 where `page_desc` = 'Plans';
            Update `page_access` SET `priority` = 64 where `page_desc` = 'Playbacks';
            Update `page_access` SET `priority` = 65 where `page_desc` = 'Prompt Lists';
            Update `page_access` SET `priority` = 66 where `page_desc` = 'Agent';
            Update `page_access` SET `priority` = 67 where `page_desc` = 'Jobs';
            Update `page_access` SET `priority` = 68 where `page_desc` = 'CDR Reports';
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `tbl_enterprise_phonebook`;");
    }
}
