<?php

use yii\db\Migration;

/**
 * Class m240101_112112_ct_service
 */
class m240101_112112_ct_service extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE `ct_services` SET `ser_name` = 'IVR' WHERE `ct_services`.`ser_name` = 'AUDIO TEXT';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240101_112112_ct_service cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240101_112112_ct_service cannot be reverted.\n";

        return false;
    }
    */
}
