<?php

use yii\db\Migration;

/**
 * Class m240710_065814_auth_item_10_7_2024
 */
class m240710_065814_auth_item_10_7_2024 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
            ('/campaign/campaign/change-action', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super_admin', '/campaign/campaign/change-action');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240710_065814_auth_item_10_7_2024 cannot be reverted.\n";

        return false;
    }
}
