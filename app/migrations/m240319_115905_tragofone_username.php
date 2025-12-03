<?php

use yii\db\Migration;

/**
 * Class m240319_115905_tragofone_username
 */
class m240319_115905_tragofone_username extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_extension_master`  ADD `trago_username` VARCHAR(100) NULL  AFTER `external_caller_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240319_115905_tragofone_username cannot be reverted.\n";

        return false;
    }
}
