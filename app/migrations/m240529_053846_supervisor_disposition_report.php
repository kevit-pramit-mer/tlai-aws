<?php

use yii\db\Migration;

/**
 * Class m240529_053846_supervisor_disposition_report
 */
class m240529_053846_supervisor_disposition_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('supervisor', '/dispositionreport/disposition-report/index'),
                ('supervisor', '/dispositionreport/disposition-report/export');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240529_053846_supervisor_disposition_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240529_053846_supervisor_disposition_report cannot be reverted.\n";

        return false;
    }
    */
}
