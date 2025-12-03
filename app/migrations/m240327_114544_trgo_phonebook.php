<?php

use yii\db\Migration;

/**
 * Class m240327_114544_trgo_phonebook
 */
class m240327_114544_trgo_phonebook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_phonebook`  ADD `trago_ed_id` INT(11) NULL  AFTER `ph_email_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_phonebook` DROP `trago_ed_id`;");
    }
}
