<?php

use yii\db\Migration;

/**
 * Class m240520_075820_extension_cdr
 */
class m240520_075820_extension_cdr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `extension_cdr` (
                `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                `from_number` varchar(100) DEFAULT NULL,
                `to_number` varchar(100) DEFAULT NULL,
                `call_id` varchar(100) DEFAULT NULL,
                `start_time` timestamp NULL DEFAULT NULL,
                `ans_time` timestamp NULL DEFAULT NULL,
                `end_time` timestamp NULL DEFAULT NULL,
                `direction` varchar(100) DEFAULT NULL
            );
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `extension_cdr`;");
    }
}
