<?php

use yii\db\Migration;

/**
 * Class m240205_061433_directory_custom_view
 */
class m240205_061433_directory_custom_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE OR REPLACE VIEW `directory_custom`  AS  select `a`.`em_id` AS `ext_id`,`b`.`dd_id` AS `domain_id`,`a`.`em_extension_number` AS `username`,`a`.`em_password` AS `param:password`,`c`.`ecs_dtmf_type` AS `param:dtmf-type`,"calltech" AS `var:user_context`,"calltech" AS `param:verto-context`,"XML" AS `param:verto-dialplan`,"verto" AS `param:jsonrpc-allowed-methods`,"demo,conference,presence" AS `param:jsonrpc-allowed-event-channels`,"{^^:sip_invite_domain=${dialed_domain}:presence_id=${dialed_user}@${dialed_domain}}${sofia_contact(*/${dialed_user}@${dialed_domain})},${verto_contact ${dialed_user}@${dialed_domain}}" AS `param:dial-string`,`a`.`em_extension_name` AS `var:effective_caller_id_name`,`a`.`em_extension_number` AS `var:effective_caller_id_number`,(select case when `c`.`ecs_multiple_registeration` = "1" then "true" else "false" end AS `param:sip-allow-multiple-registrations`) AS `param:sip-allow-multiple-registrations`,`c`.`ecs_voicemail_password` AS `param:vm-password`,"true" AS `param:vm-email-all-messages`,"true" AS `param:vm-attach-file`,`a`.`em_email` AS `param:vm-notify-mailto` from ((`ct_extension_master` `a` join `directory_domain` `b`) join `ct_extension_call_setting` `c` on(`c`.`em_id` = `a`.`em_id`)) where `a`.`em_status` = 1 group by `a`.`em_id` ;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
