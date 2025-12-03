<?php

use yii\db\Migration;

/**
 * Class m240530_091640_IVR_setting
 */
class m240530_091640_IVR_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE `auto_attendant_keys` SET `aak_key_name` = 'IVR' WHERE `auto_attendant_keys`.`aak_key_name` = 'Audio Text';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240530_091640_IVR_setting cannot be reverted.\n";

        return false;
    }
}
