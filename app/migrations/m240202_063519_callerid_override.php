<?php

use yii\db\Migration;

/**
 * Class m240202_063519_callerid_override
 */
class m240202_063519_callerid_override extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_extension_master`  ADD `external_caller_id` VARCHAR(100) NULL  AFTER `is_tragofone`;");

        $this->execute("ALTER TABLE `ct_trunk_master`  ADD `caller_id` VARCHAR(100) NULL  AFTER `trunk_cps`,  ADD `is_caller_id_override` ENUM('0','1') NOT NULL DEFAULT '0'  AFTER `caller_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_extension_master` DROP `external_caller_id`;");

        $this->execute("ALTER TABLE `ct_trunk_master` DROP `caller_id`, DROP `is_caller_id_override`;");
    }
}
