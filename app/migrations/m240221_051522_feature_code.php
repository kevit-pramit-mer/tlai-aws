<?php

use yii\db\Migration;

/**
 * Class m240221_051522_feature_code
 */
class m240221_051522_feature_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `ct_feature_master` (`feature_id`, `feature_name`, `feature_code`, `feature_desc`) VALUES
                (10, 'PARK_GROUP', '*33', 'PARK using valet_park'),
                (11, 'PICKUP_GROUP', '*34', 'PICKUP using valet_park');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240221_051522_feature_code cannot be reverted.\n";

        return false;
    }
}
