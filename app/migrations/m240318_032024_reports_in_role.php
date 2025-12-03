<?php

use yii\db\Migration;

/**
 * Class m240318_032024_reports_in_role
 */
class m240318_032024_reports_in_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `page_access` CHANGE `page_name` `page_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;");

        $this->execute("INSERT IGNORE INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES 
            (NULL, 'agentperformancereport/agent-performance-report', 'Agent Performance Report', 'N', 'N', 'N', '69'), 
            (NULL, 'timeclockreport/time-clock-report', 'Time Clock Report', 'N', 'N', 'N', '69'),  
            (NULL, 'calltimedistributionreport/call-time-distribution-report', 'Call Time Distribution', 'N', 'N', 'N', '69'),
            (NULL, 'hourlycallreport/hourly-call-report', 'Hourly Call Report', 'N', 'N', 'N', '69'),
            (NULL, 'leadperformancereport/lead-performance-report', 'Lead Performance Report', 'N', 'N', 'N', '69');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240318_032024_reports_in_role cannot be reverted.\n";

        return false;
    }
}
