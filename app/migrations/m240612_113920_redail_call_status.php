<?php

use yii\db\Migration;

/**
 * Class m240612_113920_redail_call_status
 */
class m240612_113920_redail_call_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `agent_disposition_mapping`  ADD `is_redialed` VARCHAR(10) NOT NULL DEFAULT '0'  AFTER `campaign_id`;");
        $this->execute("UPDATE `lead_comment_mapping` SET lead_status = 0");
        $this->execute("UPDATE `agent_disposition_mapping` SET is_redialed = 1");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `agent_disposition_mapping` DROP `is_redialed`;");
    }
}
