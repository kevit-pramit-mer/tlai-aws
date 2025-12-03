<?php

use yii\db\Migration;

/**
 * Class m240426_083159_trunk_live_status
 */
class m240426_083159_trunk_live_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_trunk_master`  ADD `trunk_live_status` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '1-Active ,0-InActive'  AFTER `is_caller_id_override`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_trunk_master` DROP `trunk_live_status`;");
    }
}
