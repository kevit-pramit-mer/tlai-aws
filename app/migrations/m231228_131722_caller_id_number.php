<?php

use yii\db\Migration;

/**
 * Class m231228_131722_caller_id_number
 */
class m231228_131722_caller_id_number extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_call_campaign` CHANGE `cmp_caller_id` `cmp_caller_id` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Campaign Caller Id';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231228_131722_caller_id_number cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231228_131722_caller_id_number cannot be reverted.\n";

        return false;
    }
    */
}
