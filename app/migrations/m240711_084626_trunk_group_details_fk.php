<?php

use yii\db\Migration;

/**
 * Class m240711_084626_trunk_group_details_fk
 */
class m240711_084626_trunk_group_details_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET GLOBAL FOREIGN_KEY_CHECKS=0;");

        $this->execute("ALTER TABLE `ct_trunk_group_details` ADD  FOREIGN KEY (`trunk_id`) REFERENCES `ct_trunk_master`(`trunk_id`) ON DELETE CASCADE ON UPDATE CASCADE;");

        $this->execute("SET GLOBAL FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240711_084626_trunk_group_details_fk cannot be reverted.\n";

        return false;
    }
}
