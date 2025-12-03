<?php

use yii\db\Migration;

/**
 * Class m240405_095950_agent_in_call
 */
class m240405_095950_agent_in_call extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                ('/agents/agents/in-call', 2, NULL, NULL, NULL, 1556620691, 1556620691);");
        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('agent', '/agents/agents/in-call');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240405_095950_agent_in_call cannot be reverted.\n";

        return false;
    }
}
