<?php

use yii\db\Migration;

/**
 * Class m240318_081154_is_auto_login
 */
class m240318_081154_is_auto_login extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `admin_master`  ADD `is_auto_login` ENUM('0','1') NOT NULL DEFAULT '1'  AFTER `adm_language`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `admin_master` DROP `is_auto_login`;");
    }
}
