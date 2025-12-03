<?php

use yii\db\Migration;

/**
 * Class m171010_045150_auto_attendant_details
 */
class m171010_045150_auto_attendant_details extends Migration
{
    /**
     * SafeUp method will create table 'ntc_auto_attendant_detail'.
     */
    public function safeUp()
    {
        $this->createTable('ntc_auto_attendant_detail', [
            'aad_id' => $this->primaryKey(11),
            'aam_id' => $this->primaryKey(11),
            'aad_digit' => $this->string(25),
            'aad_action' => $this->string(50),
            'aad_action_desc' => $this->string(50),
            'aad_param' => $this->string(255),
        ]);
    }

    /**
     * SafeDown method will drop table 'ntc_auto_attendant_detail'.
     *
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTable('ntc_auto_attendant_detail');
    }
}
