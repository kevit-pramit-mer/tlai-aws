<?php

use yii\db\Migration;

/**
 * Class m231213_135947_queue_status_view
 */
class m231213_135947_queue_status_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE OR REPLACE VIEW `queue_status_report`  AS  select substring_index(`qm`.`qm_name`,'_',1) AS `queue`,count(`cc`.`id`) AS `total_calls`,sum(case when (`cc`.`start_time` is null or `cc`.`start_time` = '0000-00-00 00:00:00') and (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') then 1 else 0 end) AS `calls_in_queue`,sum(case when (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and (`cc`.`ans_time` = '' or `cc`.`ans_time` is null or `cc`.`ans_time` = '0000-00-00 00:00:00') and (`cc`.`end_time` is not null or `cc`.`end_time` <> '0000-00-00 00:00:00') then 1 else 0 end) AS `abandoned_calls`,(select count(`camp_cdr`.`id`) from `camp_cdr` where `camp_cdr`.`agent_id` in (select `users_activity_log`.`user_id` from `users_activity_log` where cast(`users_activity_log`.`login_time` as date) = curdate() group by `users_activity_log`.`user_id`) and `camp_cdr`.`queue_join_time` = `cc`.`queue_join_time` and `camp_cdr`.`queue` = `cc`.`queue`) AS `logged_in_agents`,avg(case when (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and (`cc`.`ans_time` is not null or `cc`.`ans_time` <> '0000-00-00 00:00:00') then timestampdiff(SECOND,`cc`.`ans_time`,`cc`.`end_time`) else 0 end) AS `avg_call_duration`,avg(case when (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and (`cc`.`ans_time` is not null or `cc`.`ans_time` <> '0000-00-00 00:00:00') then timestampdiff(SECOND,`cc`.`queue_join_time`,`cc`.`ans_time`) else 0 end) AS `avg_queue_wait_time`,max(case when (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and (`cc`.`ans_time` is not null or `cc`.`ans_time` <> '0000-00-00 00:00:00') then timestampdiff(SECOND,`cc`.`queue_join_time`,`cc`.`ans_time`) else 0 end) AS `longest_queue_wait_time`,max(case when (`cc`.`queue_join_time` is not null or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and (`cc`.`ans_time` = '' or `cc`.`ans_time` is null or `cc`.`ans_time` = '0000-00-00 00:00:00') and (`cc`.`end_time` is not null or `cc`.`end_time` <> '0000-00-00 00:00:00') then timestampdiff(SECOND,`cc`.`queue_join_time`,`cc`.`end_time`) else 0 end) AS `longest_abandoned_calls_wait_time` from (`camp_cdr` `cc` left join `ct_queue_master` `qm` on(`qm`.`qm_id` = `cc`.`queue`)) where `cc`.`queue` <> ' ' and (`cc`.`queue_join_time` <> NULL or `cc`.`queue_join_time` <> '0000-00-00 00:00:00') and cast(`cc`.`queue_join_time` as date) = curdate() group by `cc`.`queue` ;");
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
        echo "m231213_135947_time_clock_report cannot be reverted.\n";

        return false;
    }
    */
}
