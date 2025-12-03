<?php

use yii\db\Migration;

/**
 * Class m240920_095514_ivr_change
 */
class m240920_095514_ivr_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE auto_attendant_keys SET aak_key_name = 'External Number' WHERE aak_key_name = 'Transfer to number destination';");

        $this->execute("INSERT INTO `global_web_config` (`gwc_id`, `gwc_key`, `gwc_value`, `gwc_type`, `gwc_description`) VALUES (NULL, 'TICKET_ON_HOLD_REMOVE_DAYS', '10', 'text', 'On Hold Ticket Remove Days');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240920_095514_ivr_change cannot be reverted.\n";

        return false;
    }
}
