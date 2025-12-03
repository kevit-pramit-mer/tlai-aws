<?php

use yii\db\Migration;

/**
 * Class m240318_062034_extension_im_status
 */
class m240318_062034_extension_im_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_extension_call_setting`  ADD `ecs_im_status` ENUM('0','1') NOT NULL DEFAULT '0'  AFTER `ecs_call_waiting`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_extension_call_setting` DROP `ecs_im_status`;");
    }
}
