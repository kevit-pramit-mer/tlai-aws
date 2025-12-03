<?php

use yii\db\Migration;

/**
 * Class m240617_112032_queue_sttaus_report_change_2024_06_17
 */
class m240617_112032_queue_sttaus_report_change_2024_06_17 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE OR REPLACE VIEW `queue_status_report`  AS  select substring_index(`qm`.`qm_name`,'_',1) AS `queue`,count(`qs`.`queue`) AS `total_calls`,(select count(0) from `ucdb`.`members` where `ucdb`.`members`.`queue` = `qs`.`queue` and `ucdb`.`members`.`state` = 'Waiting') AS `calls_in_queue`,sum(case when `qs`.`state` = 'Abandoned' then 1 else 0 end) AS `abandoned_calls`,(select count(`ua`.`id`) from (`users_activity_log` `ua` left join `ct_call_campaign` `ccc` on(find_in_set(`ccc`.`cmp_id`,`ua`.`campaign_name`))) where `ccc`.`cmp_queue_id` = `qm`.`qm_id` and `ua`.`logout_time` = '0000-00-00 00:00:00') AS `logged_in_agents`,avg(case when `qs`.`bridge_epoch` <> 0 and `qs`.`end_epoch` <> 0 then `qs`.`end_epoch` - `qs`.`bridge_epoch` else 0 end) AS `avg_call_duration`,avg(case when `qs`.`joined_epoch` <> 0 and `qs`.`bridge_epoch` <> 0 then `qs`.`bridge_epoch` - `qs`.`joined_epoch` else 0 end) AS `avg_queue_wait_time`,max(case when `qs`.`joined_epoch` <> 0 and `qs`.`bridge_epoch` <> 0 then `qs`.`bridge_epoch` - `qs`.`joined_epoch` else 0 end) AS `longest_queue_wait_time`,avg(case when `qs`.`joined_epoch` <> 0 and `qs`.`abandoned_epoch` <> 0 then `qs`.`abandoned_epoch` - `qs`.`joined_epoch` else 0 end) AS `longest_abandoned_calls_wait_time` from (`ct_queue_master` `qm` left join `ucdb`.`ct_queue_summary` `qs` on(`qm`.`qm_name` = convert(`qs`.`queue` using utf8mb4))) where date_format(from_unixtime(`qs`.`system_epoch`),'%Y-%m-%d') = curdate() group by `qm`.`qm_name`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240617_112032_queue_sttaus_report_change_2024_06_17 cannot be reverted.\n";

        return false;
    }
}
