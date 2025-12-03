<?php

use yii\db\Migration;

/**
 * Class m240905_123651_ip_provisioning_2
 */
class m240905_123651_ip_provisioning_2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_device_line_parameter`  ADD `value_type` VARCHAR(255) NULL  AFTER `codec`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `tbl_device_line_parameter` DROP `value_type`;");
    }
}
