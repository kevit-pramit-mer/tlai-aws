<?php

use yii\db\Migration;

/**
 * Class m231212_060927_time_clock_report
 */
class m231212_060927_time_clock_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
            ('/timeclockreport/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/timeclockreport/time-clock-report/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
            ('/timeclockreport/time-clock-report/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/timeclockreport/time-clock-report/export', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
            ('/timeclockreport/time-clock-report/agent-detail', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super_admin', '/timeclockreport/*'),
            ('super_admin', '/timeclockreport/time-clock-report/*'),
            ('super_admin', '/timeclockreport/time-clock-report/index'),
            ('super_admin', '/timeclockreport/time-clock-report/export'),
            ('super_admin', '/timeclockreport/time-clock-report/agent-detail');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231212_060927_time_clock_report cannot be reverted.\n";

        return false;
    }
    */
}
