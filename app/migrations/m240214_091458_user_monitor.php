<?php

use yii\db\Migration;

/**
 * Class m240214_091458_user_monitor
 */
class m240214_091458_user_monitor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
            ('/realtimedashboard/user-monitor/get-data', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super_admin', '/realtimedashboard/user-monitor/get-data');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240214_091458_user_monitor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240214_091458_user_monitor cannot be reverted.\n";

        return false;
    }
    */
}
