<?php

use yii\db\Migration;

/**
 * Class m240703_060720_trunk_change_2_7_2024
 */
class m240703_060720_trunk_change_2_7_2024 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE ct_trunk_master ADD trunk_display_name VARCHAR(255) NULL AFTER from_service;");

        $this->execute("UPDATE ct_trunk_master SET trunk_display_name = trunk_name where from_service = '0';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_trunk_master` DROP `trunk_display_name`;");
    }
}
