<?php

use yii\db\Migration;

/**
 * Class m240412_102913_agent_force_logout
 */
class m240412_102913_agent_force_logout extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                        ('/realtimedashboard/user-monitor/force-logout', 2, NULL, NULL, NULL, 1563449097, 1563449097),
                        ('/crm/crm/update-disposition-and-logout', 2, NULL, NULL, NULL, 1563449097, 1563449097),
                        ('/crm/crm/logout-agent', 2, NULL, NULL, NULL, 1563449097, 1563449097),
                        ('/crm/crm/login-agent', 2, NULL, NULL, NULL, 1563449097, 1563449097);");

        $this->execute("INSERT INTO `auth_item_child` (`parent`, `child`) VALUES 
                    ('super_admin', '/realtimedashboard/user-monitor/force-logout'),
                    ('agent', '/crm/crm/update-disposition-and-logout'),
                    ('agent', '/crm/crm/logout-agent'),
                    ('agent', '/crm/crm/login-agent');");

        $this->execute("ALTER TABLE `ct_disposition_type`  ADD `is_default` INT(11) NOT NULL DEFAULT '0'  AFTER `ds_type`;");

        $this->execute("ALTER TABLE `ct_disposition_master`  ADD `is_default` INT(11) NOT NULL DEFAULT '0'  AFTER `ds_description`;");

        $this->execute("INSERT INTO `ct_disposition_type` (`ds_type_id`, `ds_id`, `ds_type`, `is_default`) VALUES (NULL, 0, 'Logout From Admin', '1'), (NULL, 0, 'Browser/Tab closed', '2');");

        $this->execute("INSERT INTO `ct_disposition_master` (`ds_id`, `ds_name`, `ds_description`, `is_default`) VALUES (NULL, 'Logout From Admin', 'Logout From Admin', '1'), (NULL, 'Browser/Tab closed', 'Browser/Tab closed', '2');");

        $this->execute("INSERT INTO `ct_disposition_group_status_mapping` (`id`, `ds_group_id`, `ds_status_id`, `ds_category_id`) VALUES (NULL, (select ds_id from ct_disposition_master where is_default = '1'), (select ds_type_id from ct_disposition_type where is_default = '1'), '1'), (NULL, (select ds_id from ct_disposition_master where is_default = '2'), (select ds_type_id from ct_disposition_type where is_default = '2'), '2');");

        $this->execute("ALTER TABLE `admin_master`  ADD `force_logout` ENUM('0','1') NOT NULL DEFAULT '0'  AFTER `is_auto_login`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_disposition_type` DROP `is_default`;");
        $this->execute("ALTER TABLE `ct_disposition_master` DROP `is_default`;");
        $this->execute("ALTER TABLE `admin_master` DROP `force_logout`;");
    }
}
