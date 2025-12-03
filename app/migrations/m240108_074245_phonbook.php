<?php

use yii\db\Migration;

/**
 * Class m240108_074245_phonbook
 */
class m240108_074245_phonbook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_phonebook` CHANGE `ph_extension` `ph_extension` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Extensions';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240108_074245_phonbook cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240108_074245_phonbook cannot be reverted.\n";

        return false;
    }
    */
}
