<?php

use yii\db\Migration;

/**
 * Class m240319_090623_role_change
 */
class m240319_090623_role_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE `page_access` set page_desc='IVR' WHERE page_desc = 'Auto Attendant';");
        $this->execute("UPDATE `page_access` set page_desc='Real Time Agent Monitor Report' WHERE page_name = 'realtimedashboard/user-monitor';");
        $this->execute("INSERT IGNORE INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES 
            (NULL, 'disposition-type/disposition-type', 'Disposition Status', 'Y', 'Y', 'Y', '30');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240319_090623_role_change cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240319_090623_role_change cannot be reverted.\n";

        return false;
    }
    */
}
