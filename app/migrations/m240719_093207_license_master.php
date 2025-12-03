<?php

use yii\db\Migration;

/**
 * Class m240719_093207_license_master
 */
class m240719_093207_license_master extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `license_master` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `key` varchar(255) DEFAULT NULL,
            `value` varchar(255) DEFAULT NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `license_master`;");
    }
}
