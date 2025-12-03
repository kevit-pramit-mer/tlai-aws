<?php

use yii\db\Migration;

/**
 * Class m240402_120342_supervisor_break_time
 */
class m240402_120342_supervisor_break_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                ('/supervisor/supervisor/break-time', 2, NULL, NULL, NULL, 1556620691, 1556620691);");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('supervisor', '/supervisor/supervisor/break-time');");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240402_120342_supervisor_break_time cannot be reverted.\n";

        return false;
    }
}
