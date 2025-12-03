<?php

use yii\db\Migration;

/**
 * Class m240115_133021_break_mapping
 */
class m240115_133021_break_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `break_reason_mapping` CHANGE `camp_id` `camp_id` VARCHAR(100) NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240115_133021_break_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240115_133021_break_mapping cannot be reverted.\n";

        return false;
    }
    */
}
