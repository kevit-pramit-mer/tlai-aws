<?php

use yii\db\Migration;

/**
 * Class m240611_084301_ext_cdr_change
 */
class m240611_084301_ext_cdr_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `extension_cdr`  ADD `call_type` VARCHAR(20) NULL  AFTER `direction`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `extension_cdr` DROP `call_type`;");
    }
}
