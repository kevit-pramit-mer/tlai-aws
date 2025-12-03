<?php
use yii\db\Migration;
/**
 * Class m240603_093423_idle_time
 */
class m240603_093423_idle_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('/crm/crm/idle-time', '2', NULL, NULL, NULL, '1556620691', '1556620691');");
        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES ('agent', '/crm/crm/idle-time');");
        $this->execute("ALTER TABLE `users_activity_log`  ADD `last_activity_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'  AFTER `created_at`;");
        $this->execute("INSERT INTO `global_web_config` (`gwc_id`, `gwc_key`, `gwc_value`, `gwc_type`, `gwc_description`) VALUES (NULL, 'idle_session_timeout', '60', 'text', 'If the agent is inactive for more than this minutes, it will automatically log out.');");
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `users_activity_log` DROP `last_activity_time`;");
    }
}