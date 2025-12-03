<?php

use yii\db\Migration;

/**
 * Class m240408_054504_ext_forwarding
 */
class m240408_054504_ext_forwarding extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_extension_call_setting` CHANGE `ecs_forwarding` `ecs_forwarding` ENUM('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '0=disable 1=indi forwarding 2= fmfm forwarding 3=enable';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240408_054504_ext_forwarding cannot be reverted.\n";

        return false;
    }
}
