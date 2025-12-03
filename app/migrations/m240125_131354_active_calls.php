<?php

use yii\db\Migration;

/**
 * Class m240125_131354_active_calls
 */
class m240125_131354_active_calls extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->execute("ALTER TABLE `active_calls`  ADD `whisper_uuid` VARCHAR(255) NULL  AFTER `call_agent_time`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `active_calls` DROP `whisper_uuid`;");
    }

}
