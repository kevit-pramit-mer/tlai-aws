<?php

use yii\db\Migration;

/**
 * Class m240710_110728_pcap_10_7_2024
 */
class m240710_110728_pcap_10_7_2024 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_pcap` CHANGE `ct_url` `ct_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240710_110728_pcap_10_7_2024 cannot be reverted.\n";

        return false;
    }
}
