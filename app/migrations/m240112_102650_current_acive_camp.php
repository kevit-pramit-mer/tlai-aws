<?php

use yii\db\Migration;

/**
 * Class m240112_102650_current_acive_camp
 */
class m240112_102650_current_acive_camp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE camp_cdr  ADD current_active_camp INT(11) NULL  AFTER lead_member_id;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `camp_cdr` DROP `current_active_camp`;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240112_102650_current_acive_camp cannot be reverted.\n";

        return false;
    }
    */
}
