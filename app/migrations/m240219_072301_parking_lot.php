<?php

use yii\db\Migration;

/**
 * Class m240219_072301_parking_lot
 */
class m240219_072301_parking_lot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS `parking_lot` (
          `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT  ,
          `name` varchar(100) DEFAULT NULL,
          `park_ext` varchar(100) DEFAULT NULL,
          `slot_qty` smallint(5) DEFAULT 0,
          `park_pos_start` varchar(100) DEFAULT NULL,
          `park_pos_end` varchar(100) DEFAULT NULL,
          `grp_id` int(11) DEFAULT NULL,
          `parking_time` int(11) DEFAULT NULL COMMENT 'Amount of time call should remain on slot - seconds',
          `park_moh` varchar(100) DEFAULT NULL COMMENT 'Music on Hold IVR Prompt',
          `return_to_origin` tinyint(1) DEFAULT NULL COMMENT 'Return to originator (enable/disable). Determines further action if no one pickup calls within timeout ',
          `call_back_ring_time` int(11) DEFAULT NULL COMMENT 'Ring time for return call to originator - seconds',
          `destination_type` varchar(100) DEFAULT NULL,
          `destination_id` varchar(100) DEFAULT NULL,
          `status` enum('0','1') DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        );");

        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
            ('/parkinglot/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/parkinglot/parking-lot/change-action', 2, NULL, NULL, NULL, 1556620691, 1556620691);
            ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super_admin', '/parkinglot/*'),
            ('super_admin', '/parkinglot/parking-lot/*'),
            ('super_admin', '/parkinglot/parking-lot/index'),
            ('super_admin', '/parkinglot/parking-lot/create'),
            ('super_admin', '/parkinglot/parking-lot/update'),
            ('super_admin', '/parkinglot/parking-lot/delete'),
            ('super_admin', '/parkinglot/parking-lot/view'),
            ('super_admin', '/parkinglot/parking-lot/export'),
            ('super_admin', '/parkinglot/parking-lot/change-action');
            ");

        $this->execute("INSERT IGNORE INTO `page_access` (`page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES 
            ('parkinglot/parking-lot', 'Parking Lot', 'Y', 'Y', 'Y', '36');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `parking_lot`;");
    }
}
