<?php

use yii\db\Migration;

/**
 * Class m240221_042310_combain_extension
 */
class m240221_042310_combain_extension extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE OR REPLACE VIEW `combined_extensions`  AS  select `ct_extension_master`.`em_extension_number` AS `extension`,`ct_extension_master`.`em_id` AS `main_id`,'EXTENSION' AS `type` from `ct_extension_master` where `ct_extension_master`.`em_status` <> 'X' union select `ct_ring_group`.`rg_extension` AS `extension`,`ct_ring_group`.`rg_id` AS `main_id`,'RING GROUP' AS `type` from `ct_ring_group` where `ct_ring_group`.`rg_status` <> 'X' union select `ct_conference_master`.`cm_extension` AS `extension`,`ct_conference_master`.`cm_id` AS `main_id`,'CONFERENCE' AS `type` from `ct_conference_master` where `ct_conference_master`.`cm_status` <> 'X' union select `ct_callpark`.`cp_extension` AS `extension`,`ct_callpark`.`cp_id` AS `main_id`,'CALLPARK' AS `type` from `ct_callpark` where `ct_callpark`.`cp_status` <> 'X' union select `ct_queue_master`.`qm_extension` AS `extension`,`ct_queue_master`.`qm_id` AS `main_id`,'QUEUE' AS `type` from `ct_queue_master` where `ct_queue_master`.`qm_status` <> 'X' union select `auto_attendant_master`.`aam_extension` AS `extension`,`auto_attendant_master`.`aam_id` AS `main_id`,'IVR' AS `type` from `auto_attendant_master` where `auto_attendant_master`.`aam_status` <> 'X' and `auto_attendant_master`.`aam_extension` is not null union select `fax`.`fax_extension` AS `extension`,`fax`.`id` AS `main_id`,'FAX' AS `type` from `fax` union select `ct_extension_master`.`em_extension_number` AS `extension`,`ct_extension_master`.`em_id` AS `main_id`,'VOICEMAIL' AS `type` from (`ct_extension_master` join `ct_extension_call_setting` `cs` on(`cs`.`em_id` = `ct_extension_master`.`em_id`)) where `ct_extension_master`.`em_status` <> 'X' and `cs`.`ecs_voicemail` = '1' union select `ct_did_master`.`action_value` AS `extension`,`ct_did_master`.`action_id` AS `main_id`,'EXTERNAL' AS `type` from `ct_did_master` union select `parking_lot`.`park_ext` AS `extension`,`parking_lot`.`id` AS `main_id`,'PARKINGLOT' AS `type` from `parking_lot` where `parking_lot`.`status` <> 'X' ;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240221_042310_combain_extension cannot be reverted.\n";

        return false;
    }
}
