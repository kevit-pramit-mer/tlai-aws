<?php

use yii\db\Migration;

/**
 * Class m240904_131111_ip_provisioning_1
 */
class m240904_131111_ip_provisioning_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_device_templates_parameters` CHANGE `codec` `codec` VARCHAR(255) NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `tbl_template_details` CHANGE `codec` `codec` VARCHAR(255) NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `tbl_device_line_parameter`  ADD `codec` VARCHAR(255) NULL  AFTER `input_type`;");
        $this->execute("ALTER TABLE `tbl_device_setting` CHANGE `codec` `codec` VARCHAR(255) NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->execute("ALTER TABLE `tbl_device_line_parameter` DROP `codec`;");
    }
}
