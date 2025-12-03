<?php

use yii\db\Migration;

/**
 * Class m240103_094233_redial_call
 */
class m240103_094233_redial_call extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_redial_calls`  ADD `updated_date` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `ds_category_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_redial_calls` DROP `updated_date`;");
    }

}
