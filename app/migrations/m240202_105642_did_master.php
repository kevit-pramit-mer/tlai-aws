<?php

use yii\db\Migration;

/**
 * Class m240202_105642_did_master
 */
class m240202_105642_did_master extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE ct_did_master CHANGE did_status did_status ENUM('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1', CHANGE action_id action_id INT(11) NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240202_105642_did_master cannot be reverted.\n";

        return false;
    }
}
