<?php

use yii\db\Migration;

/**
 * Class m171010_045205_auto_attendant_keys
 */
class m171010_045205_auto_attendant_keys extends Migration
{
    /**
     * SafeUp method will create table 'ntc_auto_attendant_keys'.
     *
     */
    public function safeUp()
    {
        $this->createTable('ntc_auto_attendant_keys', [
            'aak_id' => $this->primaryKey(11),
            'aak_key_name' => $this->string(50),
            'aak_key_code' => $this->string(20),
            'aak_key_param_tpl' => $this->string(100),
        ]);
    }

    /**
     * SafeDown method will drop table 'ntc_auto_attendant_keys'.
     *
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTable('ntc_auto_attendant_keys');
    }
}
