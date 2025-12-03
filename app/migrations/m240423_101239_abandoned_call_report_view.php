<?php

use yii\db\Migration;

/**
 * Class m240423_101239_abandoned_call_report_view
 */
class m240423_101239_abandoned_call_report_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE OR REPLACE VIEW `abandoned_call_report`  AS  select `ct_queue_abandoned_calls`.`queue_name` AS `queue`,`ct_queue_abandoned_calls`.`queue_number` AS `callee_id`,`ct_queue_abandoned_calls`.`caller_id_number` AS `caller_id`,`ct_queue_abandoned_calls`.`call_status` AS `call_status`,date_format(from_unixtime(`ct_queue_abandoned_calls`.`start_time`),'%Y-%m-%d %H:%i:%s') AS `start_time`,date_format(from_unixtime(`ct_queue_abandoned_calls`.`end_time`),'%Y-%m-%d %H:%i:%s') AS `end_time`,`ct_queue_abandoned_calls`.`hold_time` AS `hold_time` from `ct_queue_abandoned_calls` union select `ct_queue_master`.`qm_name` AS `queue`,`camp_cdr`.`dial_number` AS `callee_id`,`camp_cdr`.`caller_id_num` AS `caller_id`,'Agent not answered' AS `call_status`,`camp_cdr`.`start_time` AS `start_time`,`camp_cdr`.`end_time` AS `end_time`,timestampdiff(SECOND,`camp_cdr`.`queue_join_time`,`camp_cdr`.`start_time`) AS `hold_time` from (`camp_cdr` left join `ct_queue_master` on(`ct_queue_master`.`qm_id` - `camp_cdr`.`queue`)) where `camp_cdr`.`queue` <> '' and (`camp_cdr`.`ans_time` = '' or `camp_cdr`.`ans_time` is null or `camp_cdr`.`ans_time` = '0000-00-00 00:00:00') order by `call_status` desc;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240423_101239_abandoned_call_report_view cannot be reverted.\n";

        return false;
    }
}
