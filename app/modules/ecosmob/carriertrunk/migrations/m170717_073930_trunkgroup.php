<?php

use yii\db\Migration;

class m170717_073930_trunkgroup extends Migration
{
    public function safeUp()
    {
        $this->createTable('ntc_trunk_group', [
            'trunk_grp_id' => $this->primaryKey(11),
            'trunk_grp_name' => $this->string(250),
            'trunk_grp_desc' => $this->string(500),
            'trunk_grp_lcr' => "ENUM('Y','N')",
            'trunk_grp_status' => "ENUM('Y','N','X')",
            'trunk_grp_created_at' => $this->string(25),
            'trunk_grp_updated_at' => $this->string(25),

        ]);
    }

    public function safeDown()
    {
        $this->dropTable('ntc_trunk_group');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_073930_trunkgroup cannot be reverted.\n";

        return false;
    }
    */
}
