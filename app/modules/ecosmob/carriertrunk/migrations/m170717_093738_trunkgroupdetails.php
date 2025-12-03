<?php

use yii\db\Migration;

class m170717_093738_trunkgroupdetails extends Migration
{
    public function safeUp()
    {
        $this->createTable('ct_trunk_group_details', [
            'tgd_id' => $this->primaryKey(11),
            'trunk_grp_id' => $this->integer(),
            'trunk_id' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('ntc_trunk_group_details');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_093738_trunkgroupdetails cannot be reverted.\n";

        return false;
    }
    */
}
