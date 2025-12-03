<?php

use yii\db\Migration;

/**
 * Class m240110_094352_cmp_cdr
 */
class m240110_094352_cmp_cdr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `camp_cdr` ADD `lead_member_id` INT(11) NULL AFTER `recording_file`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `camp_cdr` DROP `lead_member_id`;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240110_094352_cmp_cdr cannot be reverted.\n";

        return false;
    }
    */
}
