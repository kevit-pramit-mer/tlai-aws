<?php

use yii\db\Migration;

/**
 * Class m240502_060629_agents_permission
 */
class m240502_060629_agents_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('/agents/agents/index', '2', NULL, NULL, NULL, '1556620691', '1556620691');");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES ('agent', '/agents/agents/index');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240502_060629_agents_permission cannot be reverted.\n";

        return false;
    }
}
