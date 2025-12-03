<?php

use yii\db\Migration;

/**
 * Class m240530_051902_supervisor_lead_performance_report
 */
class m240530_051902_supervisor_lead_performance_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
            ('/leadperformancereport/lead-performance-report/get-lead-groups', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('supervisor', '/leadperformancereport/lead-performance-report/index'),
                ('supervisor', '/leadperformancereport/lead-performance-report/get-lead-groups'),
                ('supervisor', '/leadperformancereport/lead-performance-report/export');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240530_051902_supervisor_lead_performance_report cannot be reverted.\n";

        return false;
    }
}
