<?php

use yii\db\Migration;

/**
 * Class m240205_122344_did_from_service
 */
class m240205_122344_did_from_service extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_did_master`  ADD `from_service` ENUM('0','1') NOT NULL DEFAULT '0'  AFTER `is_time_based`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_did_master` DROP `from_service`;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240205_122344_did__from_service cannot be reverted.\n";

        return false;
    }
    */
}
