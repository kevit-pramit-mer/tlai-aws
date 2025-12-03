<?php

use yii\db\Migration;

/**
 * Class m171010_045141_auto_attendant_master
 */
class m171010_045141_auto_attendant_master extends Migration
{
    /**
     * SafeUp method will create table 'ntc_auto_attendant_master'.
     *
     */
    public function safeUp()
    {
        $this->createTable('ntc_auto_attendant_master', [
            'aam_id' => $this->primaryKey(11),
            'aam_name' => $this->string(20)->notNull()->unique(),
            'aam_extension' => $this->string(15),
            'aam_greet_long' => $this->string(255),
            'aam_greet_short' => $this->string(255),
            'aam_invalid_sound' => $this->string(255),
            'aam_exit_sound' => $this->string(255),
            'aam_timeout' => $this->string(5),
            'aam_max_timeout' => $this->string(5),
            'aam_inter_digit_timeout' => $this->string(5),
            'aam_max_failures' => $this->integer(2),
            'aam_digit_len' => $this->integer(11),
            'aam_language' => $this->string(30),
            'aam_direct_ext_call' => "ENUM('0','1')",
            'aam_transfer_on_failure' => "ENUM('0','1')",
            'aam_failure_prompt' => $this->string(50),
            'aam_transfer_extension' => $this->string(30),
            'aam_allow_direct_dial_extension' => $this->string(100),
            'aam_deny_direct_dial_extension' => $this->string(100),
            'tm_id' => $this->integer(11),
            'aam_mapped_id' => $this->integer(11),
            'aam_level' => $this->integer(11),
            'aam_status' => "ENUM('Y','N','X')",
        ]);
    }

    /**
     * SafeDown method will drop table 'ntc_auto_attendant_master'.
     *
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTable('ntc_auto_attendant_master');
    }
}
