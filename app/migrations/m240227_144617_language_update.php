<?php

use yii\db\Migration;

/**
 * Class m240227_144617_language_update
 */
class m240227_144617_language_update extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `admin_master`  ADD `adm_language` VARCHAR(10) NOT NULL DEFAULT 'en-US'  AFTER `auto_login_token`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `admin_master` DROP `adm_language`;");
    }
}
