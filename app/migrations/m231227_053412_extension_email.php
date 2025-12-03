<?php

use yii\db\Migration;

/**
 * Class m231227_053412_extension_email
 */
class m231227_053412_extension_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_extension_master` CHANGE `em_email` `em_email` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231227_053412_extension_email cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231227_053412_extension_email cannot be reverted.\n";

        return false;
    }
    */
}
