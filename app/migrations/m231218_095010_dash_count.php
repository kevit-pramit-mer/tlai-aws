<?php

use yii\db\Migration;

/**
 * Class m231218_095010_dash_count
 */
class m231218_095010_dash_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `dash_active_calls_count`  ADD `dash_count` INT(11) NOT NULL DEFAULT '0'  AFTER `update_time`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `dash_active_calls_count` DROP `dash_count`;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231218_095010_dash_count cannot be reverted.\n";

        return false;
    }
    */
}
