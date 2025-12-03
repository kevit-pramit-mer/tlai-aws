<?php

use yii\db\Migration;

/**
 * Class m240605_054859_auth_item_child_idle_time
 */
class m240605_054859_auth_item_child_idle_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES ('supervisor', '/crm/crm/idle-time'), ('super_admin', '/crm/crm/idle-time');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240605_054859_auth_item_child_idle_time cannot be reverted.\n";

        return false;
    }
}
