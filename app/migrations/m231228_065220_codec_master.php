<?php

use yii\db\Migration;

/**
 * Class m231228_065220_codec_master
 */
class m231228_065220_codec_master extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `ct_codec_master` (`ntc_codec_id`, `ntc_codec_name`, `ntc_codec_desc`, `ntc_codec_type`) VALUES (NULL, 'H264', 'H264', 'Video');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231228_065220_codec_master cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231228_065220_codec_master cannot be reverted.\n";

        return false;
    }
    */
}
