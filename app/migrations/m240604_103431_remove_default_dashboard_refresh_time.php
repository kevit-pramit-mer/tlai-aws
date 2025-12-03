<?php

use yii\db\Migration;

/**
 * Class m240604_103431_remove_default_dashboard_refresh_time
 */
class m240604_103431_remove_default_dashboard_refresh_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DELETE FROM `global_web_config` where gwc_key = 'realtime_dashboard_refresh_time';");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('supervisor', '/timeclockreport/time-clock-report/index'),
                ('supervisor', '/timeclockreport/time-clock-report/export'),
                ('supervisor', '/timeclockreport/time-clock-report/agent-detail');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240604_103431_remove_default_dashboard_refresh_time cannot be reverted.\n";

        return false;
    }
}
