<?php

use yii\db\Migration;

/**
 * Class m240726_070731_added_voicemail_in_ivr
 */
class m240726_070731_added_voicemail_in_ivr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auto_attendant_keys` (`aak_id`, `aak_key_name`, `aak_key_code`, `aak_key_param_tpl`) VALUES (14, 'Voicemail', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240726_070731_added_voicemail_in_ivr cannot be reverted.\n";

        return false;
    }
}
