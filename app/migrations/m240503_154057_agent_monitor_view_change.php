<?php

use yii\db\Migration;

/**
 * Class m240503_154057_agent_monitor_view_change
 */
class m240503_154057_agent_monitor_view_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE OR REPLACE VIEW `agent_monitor`  AS  select `ua`.`id` AS `id`,`ua`.`user_id` AS `user_id`,`ua`.`campaign_name` AS `campaign_name`,`ua`.`login_time` AS `login_time`,`ccc`.`cmp_name` AS `cmp_name`,concat(`am`.`adm_firstname`,' ',`am`.`adm_lastname`) AS `agent`,`em`.`em_extension_number` AS `extension_number`,(select `camp_cdr`.`dial_number` from `camp_cdr` where `camp_cdr`.`start_time` >= `ua`.`login_time` and `camp_cdr`.`agent_id` = `ua`.`user_id` and (`camp_cdr`.`ans_time` is not null or `camp_cdr`.`ans_time` <> '' or `camp_cdr`.`ans_time` <> '0000-00-00 00:00:00') and (`camp_cdr`.`end_time` is null or `camp_cdr`.`end_time` = '' or `camp_cdr`.`end_time` = '0000-00-00 00:00:00') and case when `camp_cdr`.`current_active_camp` is null then `camp_cdr`.`camp_name` else `camp_cdr`.`current_active_camp` end = convert(`ua`.`campaign_name` using utf8mb4)) AS `customer_number`,`agents`.`status` AS `status`,`agents`.`state` AS `state`,(select substring_index(`qm`.`qm_name`,'_',1) from (`ct_call_campaign` left join `ct_queue_master` `qm` on(`qm`.`qm_id` = `ct_call_campaign`.`cmp_queue_id`)) where `ct_call_campaign`.`cmp_id` = `ua`.`campaign_name`) AS `queue`,(select count(`camp_cdr`.`id`) from `camp_cdr` where `camp_cdr`.`agent_id` = `ua`.`user_id` and cast(`camp_cdr`.`start_time` as date) = curdate() and case when `camp_cdr`.`current_active_camp` is null then `camp_cdr`.`camp_name` else `camp_cdr`.`current_active_camp` end = convert(`ua`.`campaign_name` using utf8mb4)) AS `total_calls`,(select sum(timestampdiff(SECOND,`camp_cdr`.`ans_time`,`camp_cdr`.`end_time`)) from `camp_cdr` where `camp_cdr`.`agent_id` = `ua`.`user_id` and cast(`camp_cdr`.`start_time` as date) = curdate() and case when `camp_cdr`.`current_active_camp` is null then `camp_cdr`.`camp_name` else `camp_cdr`.`current_active_camp` end = convert(`ua`.`campaign_name` using utf8mb4)) AS `total_talk_time`,(select avg(timestampdiff(SECOND,`camp_cdr`.`start_time`,`camp_cdr`.`end_time`)) from `camp_cdr` where `camp_cdr`.`agent_id` = `ua`.`user_id` and cast(`camp_cdr`.`start_time` as date) = curdate() and case when `camp_cdr`.`current_active_camp` is null then `camp_cdr`.`camp_name` else `camp_cdr`.`current_active_camp` end = convert(`ua`.`campaign_name` using utf8mb4)) AS `avg_call_duration`,(select avg(timestampdiff(SECOND,`camp_cdr`.`start_time`,`camp_cdr`.`ans_time`)) from `camp_cdr` where `camp_cdr`.`agent_id` = `ua`.`user_id` and cast(`camp_cdr`.`start_time` as date) = curdate() and `camp_cdr`.`start_time` <= case when `ua`.`logout_time` <> '0000-00-00 00:00:00' then `ua`.`logout_time` else current_timestamp() end and case when `camp_cdr`.`current_active_camp` is null then `camp_cdr`.`camp_name` else `camp_cdr`.`current_active_camp` end = convert(`ua`.`campaign_name` using utf8mb4)) AS `avg_wait_time`,(select sum(timestampdiff(SECOND,`users_activity_log`.`login_time`,case when `users_activity_log`.`logout_time` <> '0000-00-00 00:00:00' then `users_activity_log`.`logout_time` else current_timestamp() end)) from `users_activity_log` where `users_activity_log`.`user_id` = `ua`.`user_id` and cast(`users_activity_log`.`login_time` as date) = curdate() and `users_activity_log`.`campaign_name` = `ua`.`campaign_name`) AS `login_hour`,(select sum(timestampdiff(SECOND,`break_reason_mapping`.`in_time`,case when `break_reason_mapping`.`out_time` <> '0000-00-00 00:00:00' then `break_reason_mapping`.`out_time` else current_timestamp() end)) from `break_reason_mapping` where cast(`break_reason_mapping`.`in_time` as date) = curdate() and `break_reason_mapping`.`user_id` = `ua`.`user_id` and find_in_set(convert(`ua`.`campaign_name` using utf8mb4),`break_reason_mapping`.`camp_id`)) AS `total_break_time`,(select count(`break_reason_mapping`.`id`) from `break_reason_mapping` where cast(`break_reason_mapping`.`in_time` as date) = curdate() and `break_reason_mapping`.`user_id` = `ua`.`user_id` and find_in_set(convert(`ua`.`campaign_name` using utf8mb4),`break_reason_mapping`.`camp_id`)) AS `total_breaks` from ((((`users_activity_log` `ua` left join `agents` on(substring_index(`agents`.`name`,'_',1) = `ua`.`user_id`)) left join `admin_master` `am` on(`am`.`adm_id` = `ua`.`user_id`)) left join `ct_extension_master` `em` on(`em`.`em_id` = `am`.`adm_mapped_extension`)) left join `ct_call_campaign` `ccc` on(`ccc`.`cmp_id` = `ua`.`campaign_name`)) where `am`.`adm_status` = '1' and `am`.`adm_is_admin` = 'agent' and `ua`.`logout_time` = '0000-00-00 00:00:00' and `agents`.`status` <> 'Logged Out';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240503_154057_agent_monitor_view_change cannot be reverted.\n";

        return false;
    }
}
