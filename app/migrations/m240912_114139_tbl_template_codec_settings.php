<?php

use yii\db\Migration;

/**
 * Class m240912_114139_tbl_template_codec_settings
 */
class m240912_114139_tbl_template_codec_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `tbl_template_codec_settings` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `parameter_key` varchar(255) DEFAULT NULL,
  `codec` varchar(255) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL
);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `tbl_template_codec_settings`;");
    }
}
