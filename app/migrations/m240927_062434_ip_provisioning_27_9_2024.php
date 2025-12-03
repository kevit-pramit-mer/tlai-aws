<?php

use yii\db\Migration;

/**
 * Class m240927_062434_ip_provisioning_27_9_2024
 */
class m240927_062434_ip_provisioning_27_9_2024 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_devices`  ADD `provisioning_status` TINYINT(1) NULL  AFTER `model_id`;");

        $this->execute("ALTER TABLE `tbl_device_setting`  ADD `is_change` INT(11) NOT NULL DEFAULT '1'  AFTER `variable_source`;");

        $this->execute("ALTER TABLE `tbl_device_line_parameter`  ADD `is_change` INT(11) NOT NULL DEFAULT '1'  AFTER `value_type`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `tbl_devices` DROP `provisioning_status`;");
        $this->execute("ALTER TABLE `tbl_device_setting` DROP `is_change`;");
        $this->execute("ALTER TABLE `tbl_device_line_parameter` DROP `is_change`;");
    }
}
