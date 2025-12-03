<?php

use yii\db\Migration;

/**
 * Class m240910_085441_ip_provisioning_log
 */
class m240910_085441_ip_provisioning_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `tbl_ip_provisioning_log` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `parameter_key` varchar(255) DEFAULT NULL,
  `request` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `response_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `tbl_ip_provisioning_log`;");
    }
}
