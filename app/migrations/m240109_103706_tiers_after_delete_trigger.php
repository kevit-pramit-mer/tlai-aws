<?php

use yii\db\Migration;

/**
 * Class m240109_103706_tiers_after_delete_trigger
 */
class m240109_103706_tiers_after_delete_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS tiers_after_delete");
        $this->execute("CREATE TRIGGER `tiers_after_delete` AFTER DELETE ON `tiers`
    FOR EACH ROW DELETE FROM ucdb.tiers WHERE agent = OLD.agent");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS tiers_after_delete");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240109_103706_tiers_after_delete_trigger cannot be reverted.\n";

        return false;
    }
    */
}
