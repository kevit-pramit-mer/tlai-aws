<?php

use yii\db\Migration;

class m170718_060711_outboundroutes extends Migration
{
    public function safeUp()
    {
        $this->createTable('ntc_outbound_routing', [
            'or_id' => $this->primaryKey(),
            'or_prefix' => $this->string(25),
            'group_id' => $this->integer(),
            // 'trunk_grp_status' => "ENUM('Y','N','X')",
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('ntc_outbound_routing');
    }
}
