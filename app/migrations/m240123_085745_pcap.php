<?php

use yii\db\Migration;

/**
 * Class m240123_085745_pcap
 */
class m240123_085745_pcap extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_pcap`  ADD `ct_name` VARCHAR(100) NULL  AFTER `ct_id`;");

        $this->execute("ALTER TABLE `ct_pcap`  ADD `filter` ENUM('Any','IPV4','IPV6') NOT NULL DEFAULT 'Any'  AFTER `ct_name`,  ADD `buffer_size` INT(11) NULL  AFTER `filter`,  ADD `packets_limit` INT(11) NULL  AFTER `buffer_size`;");

        $this->execute("ALTER TABLE `ct_pcap` CHANGE `ct_status` `ct_status` ENUM('start','stop') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_pcap` DROP `ct_name`, DROP `filter`, DROP `buffer_size`, DROP `packets_limit`;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240123_085745_pcap cannot be reverted.\n";

        return false;
    }
    */
}
