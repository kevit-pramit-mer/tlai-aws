<?php

use yii\db\Migration;

/**
 * Class m240125_122236_triggers
 */
class m240125_122236_triggers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS did_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS did_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS did_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_delete");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_update");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_delete");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_update");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS tier_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS tiers_after_delete");
        
        $this->execute("CREATE TRIGGER `did_after_delete_trigger` AFTER DELETE ON `ct_did_master` FOR EACH ROW BEGIN

    DECLARE param_tenant_domain VARCHAR(255);

    SELECT domain INTO param_tenant_domain
    FROM ct_tenant_info
        LIMIT 1;
    DELETE FROM ucdb.did_domain_mapping
    WHERE id = OLD.did_id AND domain = param_tenant_domain;
END");
        $this->execute("CREATE TRIGGER `did_after_insert_trigger` AFTER INSERT ON `ct_did_master` FOR EACH ROW BEGIN
    DECLARE param_domain VARCHAR(255);

    SELECT domain INTO param_domain
    FROM ct_tenant_info
        LIMIT 1;

    INSERT INTO ucdb.did_domain_mapping (
        id,
        did,
        domain
    ) VALUES (
                 NEW.did_id,
                 NEW.did_number,
                 param_domain
             );

END");

        $this->execute("CREATE TRIGGER `did_after_update_trigger` AFTER UPDATE ON `ct_did_master` FOR EACH ROW BEGIN
    DECLARE param_tenant_domain VARCHAR(255);

    SELECT domain INTO param_tenant_domain
    FROM ct_tenant_info
        LIMIT 1;

    UPDATE ucdb.did_domain_mapping
    SET
        did = NEW.did_number
    WHERE id = NEW.did_id AND domain = param_tenant_domain;
END");

        $this->execute("CREATE TRIGGER `trunk_after_delete_trigger` AFTER DELETE ON `ct_trunk_master` FOR EACH ROW BEGIN

    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;
    DELETE FROM ucdb.ct_trunk_master
    WHERE trunk_id = OLD.trunk_id AND tenant_uuid = param_tenant_uuid;
END");

        $this->execute("CREATE TRIGGER `trunk_after_insert_trigger` AFTER INSERT ON `ct_trunk_master` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;

    INSERT INTO ucdb.ct_trunk_master (
        trunk_id,
        trunk_name,
        trunk_ip,
        trunk_register,
        trunk_username,
        trunk_password,
        trunk_add_prefix,
        trunk_proxy_ip,
        trunk_port,
        trunk_mask,
        trunk_status,
        trunk_ip_type,
        trunk_ip_version,
        trunk_absolute_codec,
        trunk_fax_support,
        trunk_protocol,
        trunk_channels,
        trunk_cps,
        tenant_uuid
    ) VALUES (
                 NEW.trunk_id,
                 NEW.trunk_name,
                 NEW.trunk_ip,
                 NEW.trunk_register,
                 NEW.trunk_username,
                 NEW.trunk_password,
                 NEW.trunk_add_prefix,
                 NEW.trunk_proxy_ip,
                 NEW.trunk_port,
                 NEW.trunk_mask,
                 NEW.trunk_status,
                 NEW.trunk_ip_type,
                 NEW.trunk_ip_version,
                 NEW.trunk_absolute_codec,
                 NEW.trunk_fax_support,
                 NEW.trunk_protocol,
                 NEW.trunk_channels,
                 NEW.trunk_cps,
                 param_tenant_uuid
             );

END");

        $this->execute("CREATE TRIGGER `trunk_after_update_trigger` AFTER UPDATE ON `ct_trunk_master` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;

    UPDATE ucdb.ct_trunk_master
    SET
        trunk_name = NEW.trunk_name,
        trunk_ip = NEW.trunk_ip,
        trunk_register = NEW.trunk_register,
        trunk_username = NEW.trunk_username,
        trunk_password = NEW.trunk_password,
        trunk_add_prefix = NEW.trunk_add_prefix,
        trunk_proxy_ip = NEW.trunk_proxy_ip,
        trunk_port = NEW.trunk_port,
        trunk_mask = NEW.trunk_mask,
        trunk_status = NEW.trunk_status,
        trunk_ip_type = NEW.trunk_ip_type,
        trunk_ip_version = NEW.trunk_ip_version,
        trunk_absolute_codec = NEW.trunk_absolute_codec,
        trunk_fax_support = NEW.trunk_fax_support,
        trunk_protocol = NEW.trunk_protocol,
        trunk_channels = NEW.trunk_channels,
        trunk_cps = NEW.trunk_cps
    WHERE trunk_id = NEW.trunk_id AND tenant_uuid = param_tenant_uuid;
END");

        $this->execute("CREATE TRIGGER `access_restriction_after_delete` AFTER DELETE ON `ct_access_restriction` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;

    DELETE FROM ucdb.ct_access_restriction
    WHERE
            ar_id = OLD.ar_id AND tenant_uuid = param_tenant_uuid;
END");

        $this->execute("CREATE TRIGGER `access_restriction_after_insert` AFTER INSERT ON `ct_access_restriction` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info LIMIT 1;

    INSERT INTO ucdb.ct_access_restriction (
        ar_id, ar_ipaddress, ar_maskbit, ar_description, ar_status, tenant_uuid
    )
    VALUES (
               NEW.ar_id, NEW.ar_ipaddress, NEW.ar_maskbit, NEW.ar_description, NEW.ar_status, param_tenant_uuid
           );
END");

        $this->execute("CREATE TRIGGER `access_restriction_after_update` AFTER UPDATE ON `ct_access_restriction` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info LIMIT 1;

    UPDATE ucdb.ct_access_restriction
    SET
        ar_ipaddress = NEW.ar_ipaddress,
        ar_maskbit = NEW.ar_maskbit,
        ar_description = NEW.ar_description,
        ar_status = NEW.ar_status
    WHERE
            ar_id = NEW.ar_id AND tenant_uuid = param_tenant_uuid;
END");

        $this->execute("CREATE TRIGGER `agent_after_delete` AFTER DELETE ON `agents` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;
    DELETE FROM ucdb.agents
    WHERE name = OLD.name AND tenant_uuid = param_tenant_uuid;
END");

        $this->execute("CREATE TRIGGER `agent_after_insert` AFTER INSERT ON `agents` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info LIMIT 1;

    INSERT INTO ucdb.agents (
        `name`, `system`, `uuid`, `type`, `contact`, `status`, `state`,
        `max_no_answer`, `wrap_up_time`, `reject_delay_time`, `busy_delay_time`,
        `no_answer_delay_time`, `last_bridge_start`, `last_bridge_end`,
        `last_offered_call`, `last_status_change`, `no_answer_count`,
        `calls_answered`, `talk_time`, `ready_time`, `reject_call_count`,
        `external_calls_count`, `login_extension`,`tenant_uuid`
    ) VALUES (
                 NEW.`name`, NEW.`system`, NEW.`uuid`, NEW.`type`, NEW.`contact`,
                 NEW.`status`, NEW.`state`, NEW.`max_no_answer`, NEW.`wrap_up_time`,
                 NEW.`reject_delay_time`, NEW.`busy_delay_time`, NEW.`no_answer_delay_time`,
                 NEW.`last_bridge_start`, NEW.`last_bridge_end`, NEW.`last_offered_call`,
                 NEW.`last_status_change`, NEW.`no_answer_count`, NEW.`calls_answered`,
                 NEW.`talk_time`, NEW.`ready_time`, NEW.`reject_call_count`,
                 NEW.`external_calls_count`, NEW.`login_extension`, param_tenant_uuid
             );
END");

        $this->execute("CREATE TRIGGER `agent_after_update` AFTER UPDATE ON `agents`
     FOR EACH ROW BEGIN
      DECLARE param_tenant_uuid VARCHAR(255);
      SELECT tenant_uuid INTO param_tenant_uuid FROM ct_tenant_info LIMIT 1;  
        UPDATE ucdb.agents
        SET
          contact = NEW.contact,
          status = NEW.status,
          state = NEW.state
        WHERE
          name = NEW.name AND tenant_uuid = param_tenant_uuid;
    END");

        $this->execute("CREATE TRIGGER `queue_after_delete_trigger` AFTER DELETE ON `ct_queue_master` FOR EACH ROW BEGIN

    DELETE FROM ucdb.ct_queue_master
    WHERE qm_name = OLD.qm_name;
END");

        $this->execute("CREATE TRIGGER `queue_after_insert_trigger` AFTER INSERT ON `ct_queue_master` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;

    INSERT INTO ucdb.ct_queue_master
    (qm_id, qm_name, qm_extension, qm_strategy, qm_moh, qm_language, qm_info_prompt, qm_max_waiting_calls, qm_timeout_sec, qm_wrap_up_time, qm_is_recording, qm_exit_caller_if_no_agent_available, qm_play_position_on_enter, qm_play_position_periodically, qm_periodic_announcement, qm_periodic_announcement_prompt, qm_display_name_in_caller_id, qm_is_failed, qm_failed_service_id, qm_failed_action, qm_is_interrupt, qm_exit_key, qm_interrupt_service_id, qm_interrupt_action, qm_auto_answer, qm_callback, qm_status, qm_weight, tenant_uuid)
    VALUES
        (NEW.qm_id,NEW.qm_name,NEW.qm_extension, NEW.qm_strategy,NEW.qm_moh,NEW.qm_language, NEW.qm_info_prompt,NEW.qm_max_waiting_calls, NEW.qm_timeout_sec,NEW.qm_wrap_up_time, NEW.qm_is_recording,NEW.qm_exit_caller_if_no_agent_available,NEW.qm_play_position_on_enter, NEW.qm_play_position_periodically, NEW.qm_periodic_announcement,NEW.qm_periodic_announcement_prompt,NEW.qm_display_name_in_caller_id, NEW.qm_is_failed,NEW.qm_failed_service_id,NEW.qm_failed_action,NEW.qm_is_interrupt, NEW.qm_exit_key,NEW.qm_interrupt_service_id,NEW.qm_interrupt_action,NEW.qm_auto_answer,NEW.qm_callback, NEW.qm_status,NEW.qm_weight, param_tenant_uuid);
END");

        $this->execute("CREATE TRIGGER `queue_after_update_trigger` AFTER UPDATE ON `ct_queue_master` FOR EACH ROW BEGIN
    DECLARE param_tenant_uuid VARCHAR(255);

    SELECT tenant_uuid INTO param_tenant_uuid
    FROM ct_tenant_info
             LIMIT 1;

    UPDATE ucdb.ct_queue_master
    SET qm_name = NEW.qm_name,
        qm_extension = NEW.qm_extension,
        qm_strategy = NEW.qm_strategy,
        qm_moh = NEW.qm_moh,
        qm_language = NEW.qm_language,
        qm_info_prompt = NEW.qm_info_prompt,
        qm_max_waiting_calls = NEW.qm_max_waiting_calls,
        qm_timeout_sec = NEW.qm_timeout_sec,
        qm_wrap_up_time = NEW.qm_wrap_up_time,
        qm_is_recording = NEW.qm_is_recording,
        qm_exit_caller_if_no_agent_available = NEW.qm_exit_caller_if_no_agent_available,
        qm_play_position_on_enter = NEW.qm_play_position_on_enter,
        qm_play_position_periodically = NEW.qm_play_position_periodically,
        qm_periodic_announcement = NEW.qm_periodic_announcement,
        qm_periodic_announcement_prompt = NEW.qm_periodic_announcement_prompt,
        qm_display_name_in_caller_id = NEW.qm_display_name_in_caller_id,
        qm_is_failed = NEW.qm_is_failed,
        qm_failed_service_id = NEW.qm_failed_service_id,
        qm_failed_action = NEW.qm_failed_action,
        qm_is_interrupt = NEW.qm_is_interrupt,
        qm_exit_key = NEW.qm_exit_key,
        qm_interrupt_service_id = NEW.qm_interrupt_service_id,
        qm_interrupt_action = NEW.qm_interrupt_action,
        qm_auto_answer = NEW.qm_auto_answer,
        qm_callback = NEW.qm_callback,
        qm_status = NEW.qm_status,
        qm_weight = NEW.qm_weight
    WHERE qm_id = NEW.qm_id AND tenant_uuid = param_tenant_uuid;

END");

        $this->execute("CREATE TRIGGER `tier_after_insert` AFTER INSERT ON `tiers` FOR EACH ROW BEGIN
    INSERT INTO ucdb.tiers (queue, agent, state, level, position)
    VALUES (NEW.queue, NEW.agent, NEW.state, NEW.level, NEW.position);
END");

        $this->execute("CREATE TRIGGER `tiers_after_delete` AFTER DELETE ON `tiers`
    FOR EACH ROW DELETE FROM ucdb.tiers WHERE agent = OLD.agent");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS did_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS did_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS did_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS trunk_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_delete");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS access_restriction_after_update");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_delete");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS agent_after_update");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_delete_trigger");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_insert_trigger");
        $this->execute("DROP TRIGGER IF EXISTS queue_after_update_trigger");
        $this->execute("DROP TRIGGER IF EXISTS tier_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS tiers_after_delete");
    }

}
