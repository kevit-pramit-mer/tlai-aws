<?php

use yii\db\Migration;

/**
 * Class m240221_063146_extension_blf
 */
class m240221_063146_extension_blf extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS `ct_extension_blf` (
            `es_id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT  ,
            `em_id` int(11) DEFAULT NULL,
            `digits` int(11) DEFAULT NULL,
            `extension` varchar(30) DEFAULT NULL
        );");

        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
            ('/blf/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/blf/extension-blf/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/blf/extension-blf/blf', 2, NULL, NULL, NULL, 1556620691, 1556620691);
            ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `ct_extension_blf`;");
    }
}
