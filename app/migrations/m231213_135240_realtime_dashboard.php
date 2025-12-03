<?php

use yii\db\Migration;

/**
 * Class m231213_135240_realtime_dashboard
 */
class m231213_135240_realtime_dashboard extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
            ('/realtimedashboard/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/active-calls/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/active-calls/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/active-calls/active-calls-export', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/campaign-performance/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/campaign-performance/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/campaign-performance/export', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/queue-status/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/queue-status/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/queue-status/export', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
            ('/realtimedashboard/sip-extension/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/sip-extension/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/sip-extension/sip-reg-export', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/sip-extension/get-data', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/user-monitor/*', '2', NULL, NULL, NULL, '1556620691', '1556620691'), 
            ('/realtimedashboard/user-monitor/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
            ('/realtimedashboard/user-monitor/export', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super_admin', '/realtimedashboard/*'),
            ('super_admin', '/realtimedashboard/active-calls/*'),
            ('super_admin', '/realtimedashboard/active-calls/index'),
            ('super_admin', '/realtimedashboard/active-calls/active-calls-export'),
            ('super_admin', '/realtimedashboard/campaign-performance/*'),
            ('super_admin', '/realtimedashboard/campaign-performance/index'),
            ('super_admin', '/realtimedashboard/campaign-performance/export'),
            ('super_admin', '/realtimedashboard/queue-status/*'),
            ('super_admin', '/realtimedashboard/queue-status/index'),
            ('super_admin', '/realtimedashboard/queue-status/export'),
            ('super_admin', '/realtimedashboard/sip-extension/*'),
            ('super_admin', '/realtimedashboard/sip-extension/index'),
            ('super_admin', '/realtimedashboard/sip-extension/sip-reg-export'),
            ('super_admin', '/realtimedashboard/sip-extension/get-data'),
            ('super_admin', '/realtimedashboard/user-monitor/*'),
            ('super_admin', '/realtimedashboard/user-monitor/index'),
            ('super_admin', '/realtimedashboard/user-monitor/export');
        ");

        $this->execute("INSERT IGNORE INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES 
            (NULL, 'realtimedashboard/sip-extension', 'Real Time SIP Extension Registration Status Report', 'N', 'N', 'N', '69'), 
            (NULL, 'realtimedashboard/user-monitor', 'Real Time Agent Monitor Report', 'N', 'N', 'N', '70'), (NULL, 'realtimedashboard/queue-status', 'Real Time Queue Status Report', 'N', 'N', 'N', '71'), (NULL, 'realtimedashboard/active-calls', 'Real Time Active Calls Report', 'N', 'N', 'N', '72'),
            (NULL, 'realtimedashboard/campaign-performance', 'Real Time Campaign Performance Report', 'N', 'N', 'N', '72');
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
        echo "m231213_135240_realtime_dashboard cannot be reverted.\n";

        return false;
    }
    */
}
