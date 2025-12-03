<?php

use yii\db\Migration;

class m170714_055549_siptrunk extends Migration
{
    public function safeUp()
    {
        $this->createTable('ntc_trunk_master', [
            'trunk_id' => $this->primaryKey(11),
            'trunk_name' => $this->string(25),
            'trunk_ip' => $this->string(25),
            'trunk_register' => "ENUM('Y','N')",
            'trunk_username' => $this->string(25),
            'trunk_password' => $this->string(25),
            'trunk_add_prefix' => $this->string(25),
            'trunk_remove_prefix' => $this->string(25),
            'rc_id' => $this->integer(11)->notNull(),
            'trunk_status' => "ENUM('Y','N','X')",
            'trunk_created_at' => $this->string(256),
            'trunk_updated_at' => $this->string(256),

        ]);
    }

    public function safeDown()
    {
        $this->dropTable('ntc_trunk_master');
    }
}
