<?php

use yii\db\Migration;

/**
 * Class m240716_040232_license_management
 */
class m240716_040232_license_management extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `license_ticket_management` (
              `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
              `date` date DEFAULT NULL,
              `allocated` text DEFAULT NULL,
              `requested` text DEFAULT NULL,
              `status` varchar(20) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
            );
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `license_ticket_management`;");
    }
}
