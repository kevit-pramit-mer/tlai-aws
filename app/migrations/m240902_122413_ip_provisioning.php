<?php

use yii\db\Migration;

/**
 * Class m240902_122413_ip_provisioning
 */
class m240902_122413_ip_provisioning extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `tbl_device_templates` (
  `device_templates_id` char(36) PRIMARY KEY NOT NULL,
  `template_name` text DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `supported_models_id` varchar(255) DEFAULT NULL,
  `voipservice_key` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL);");

        $this->execute("CREATE TABLE `tbl_device_templates_parameters` (
  `id` char(36) PRIMARY KEY NOT NULL,
  `device_templates_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `parameter_name` varchar(255) DEFAULT NULL,
  `is_object` varchar(255) DEFAULT NULL,
  `is_writable` varchar(255) DEFAULT NULL,
  `parameter_value` varchar(255) DEFAULT NULL,
  `value_type` varchar(255) DEFAULT NULL,
  `parameter_label` varchar(255) DEFAULT NULL,
  `input_type` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT NULL,
  `voice_profile` tinyint(1) DEFAULT NULL,
  `codec` tinyint(1) DEFAULT NULL,
  `value_source` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
);");

        $this->execute("CREATE TABLE `tbl_devices` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT NULL,
  `template_master_id` int(11) DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
);");

        $this->execute("CREATE TABLE `tbl_device_line_parameter` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT NULL,
  `parameter_label` varchar(255) DEFAULT NULL,
  `profile_number` int(11) DEFAULT NULL,
  `parameter_name` varchar(255) DEFAULT NULL,
  `parameter_key` varchar(255) DEFAULT NULL,
  `value_source` varchar(255) DEFAULT NULL,
  `variable_source` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `is_writable` varchar(255) DEFAULT NULL,
  `input_type` varchar(255) DEFAULT NULL
);");

        $this->execute("CREATE TABLE `tbl_device_setting` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `parameter_name` varchar(255) DEFAULT NULL,
  `is_object` varchar(255) DEFAULT NULL,
  `is_writable` varchar(255) DEFAULT NULL,
  `parameter_value` varchar(255) DEFAULT NULL,
  `value_type` varchar(255) DEFAULT NULL,
  `parameter_label` varchar(255) DEFAULT NULL,
  `input_type` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT NULL,
  `voice_profile` tinyint(1) DEFAULT NULL,
  `codec` tinyint(1) DEFAULT NULL,
  `value_source` varchar(255) DEFAULT NULL,
  `variable_source` varchar(255) DEFAULT NULL
);");

        $this->execute("CREATE TABLE `tbl_template_master` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `device_template_id` char(36) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `supported_models_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
);");

        $this->execute("CREATE TABLE `tbl_template_details` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `template_id` char(36) DEFAULT NULL,
  `parameter_name` varchar(255) DEFAULT NULL,
  `is_object` varchar(255) DEFAULT NULL,
  `is_writable` varchar(255) DEFAULT NULL,
  `parameter_value` varchar(255) DEFAULT NULL,
  `value_type` varchar(255) DEFAULT NULL,
  `parameter_label` varchar(255) DEFAULT NULL,
  `input_type` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT NULL,
  `voice_profile` tinyint(1) DEFAULT NULL,
  `codec` tinyint(1) DEFAULT NULL,
  `value_source` varchar(255) NOT NULL DEFAULT 'Device Specific',
  `variable_source` varchar(255) DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0
);");

        $this->execute("CREATE TABLE `tbl_phone_models` (
  `p_id` char(36) NOT NULL,
  `pv_id` int(11) NOT NULL,
  `p_model` varchar(255) NOT NULL,
  `p_lines` int(11) NOT NULL
);");

        $this->execute("INSERT INTO `tbl_phone_models` (`p_id`, `pv_id`, `p_model`, `p_lines`) VALUES
('1', 1, 'VVX-300', 32),
('2', 1, 'VVX-400', 32),
('3', 1, 'SOUNDSTATION-6000', 1),
('4', 1, 'VVX-310', 32),
('5', 1, 'VVX-410', 32),
('6', 2, 'MP112-FXS', 2),
('7', 3, 'SPA-112', 2),
('8', 2, 'MP114-FXS', 4),
('9', 1, 'SOUNDPOINT-IP-550', 4),
('10', 1, 'SOUNDPOINT-IP-335', 2),
('11', 1, 'SOUNDPOINT-IP-450', 3),
('12', 1, 'SOUNDPOINT-IP-560', 4),
('13', 1, 'SOUNDPOINT-IP-650', 34),
('14', 1, 'SOUNDPOINT-IP-670', 34),
('15', 1, 'VVX-301', 6),
('16', 1, 'VVX-311', 6),
('17', 1, 'VVX-401', 12),
('18', 1, 'VVX-411', 12),
('19', 1, 'VVX-500', 34),
('20', 1, 'VVX-600', 16),
('41', 9, 'JITSI', 100),
('49', 1, 'VVX-201', 2),
('53', 1, 'VVX-101', 1),
('57', 1, 'VVX-450', 12),
('61', 1, 'VVX-350', 6),
('65', 1, 'VVX-250', 4),
('69', 1, 'VVX-150', 2),
('74', 1, 'VVX-501', 34),
('78', 18, 'T42S', 12),
('82', 18, 'T54W', 16),
('86', 18, 'T53W', 12),
('90', 18, 'CP960', 1),
('94', 18, 'W60B', 0),
('98', 18, 'T43U', 12),
('102', 18, 'T57W', 16),
('106', 18, 'T33G', 4),
('110', 18, 'T46U', 16),
('112', 18, 'T46S', 16),
('114', 18, 'T31P', 2);");

        $this->execute("CREATE TABLE `tbl_phone_vendor` (
  `pv_id` char(36) PRIMARY KEY NOT NULL,
  `pv_name` varchar(255) NOT NULL,
  `pv_firmware_check` enum('0','1') NOT NULL
);");

        $this->execute("INSERT INTO `tbl_phone_vendor` (`pv_id`, `pv_name`, `pv_firmware_check`) VALUES
('1', 'polycom', '1'),
('18', 'yealink', '1'),
('2', 'audiocodes', '1'),
('3', 'cisco', '0'),
('9', 'jitsi', '0');");

        $this->execute("CREATE OR REPLACE VIEW `extension_view`  AS  select `em`.`em_id` AS `em_id`,`em`.`em_extension_name` AS `em_extension_name`,`em`.`em_extension_number` AS `em_extension_number`,`em`.`em_password` AS `em_password`,`em`.`em_plan_id` AS `em_plan_id`,`em`.`em_web_password` AS `em_web_password`,`em`.`em_status` AS `em_status`,`em`.`em_shift_id` AS `em_shift_id`,`em`.`em_group_id` AS `em_group_id`,`em`.`em_language_id` AS `em_language_id`,`em`.`em_email` AS `em_email`,`em`.`em_timezone_id` AS `em_timezone_id`,`em`.`is_phonebook` AS `is_phonebook`,`em`.`em_moh` AS `em_moh`,`em`.`em_token` AS `em_token`,`em`.`trago_user_id` AS `trago_user_id`,`em`.`is_tragofone` AS `is_tragofone`,`em`.`external_caller_id` AS `external_caller_id`,`em`.`trago_username` AS `trago_username`,`ecs`.`ecs_id` AS `ecs_id`,`ecs`.`ecs_max_calls` AS `ecs_max_calls`,`ecs`.`ecs_ring_timeout` AS `ecs_ring_timeout`,`ecs`.`ecs_call_timeout` AS `ecs_call_timeout`,`ecs`.`ecs_ob_max_timeout` AS `ecs_ob_max_timeout`,`ecs`.`ecs_auto_recording` AS `ecs_auto_recording`,`ecs`.`ecs_dtmf_type` AS `ecs_dtmf_type`,`ecs`.`ecs_video_calling` AS `ecs_video_calling`,`ecs`.`ecs_bypass_media` AS `ecs_bypass_media`,`ecs`.`ecs_srtp` AS `ecs_srtp`,`ecs`.`ecs_force_record` AS `ecs_force_record`,`ecs`.`ecs_moh` AS `ecs_moh`,`ecs`.`ecs_audio_codecs` AS `ecs_audio_codecs`,`ecs`.`ecs_video_codecs` AS `ecs_video_codecs`,`ecs`.`ecs_dial_out` AS `ecs_dial_out`,`ecs`.`ecs_forwarding` AS `ecs_forwarding`,`ecs`.`ecs_voicemail` AS `ecs_voicemail`,`ecs`.`ecs_voicemail_password` AS `ecs_voicemail_password`,`ecs`.`ecs_fax2mail` AS `ecs_fax2mail`,`ecs`.`ecs_feature_code_pin` AS `ecs_feature_code_pin`,`ecs`.`ecs_multiple_registeration` AS `ecs_multiple_registeration`,`ecs`.`ecs_blacklist` AS `ecs_blacklist`,`ecs`.`ecs_accept_blocked_caller_id` AS `ecs_accept_blocked_caller_id`,`ecs`.`ecs_call_redial` AS `ecs_call_redial`,`ecs`.`ecs_bargein` AS `ecs_bargein`,`ecs`.`ecs_park` AS `ecs_park`,`ecs`.`ecs_busy_call_back` AS `ecs_busy_call_back`,`ecs`.`ecs_do_not_disturb` AS `ecs_do_not_disturb`,`ecs`.`ecs_whitelist` AS `ecs_whitelist`,`ecs`.`ecs_caller_id_block` AS `ecs_caller_id_block`,`ecs`.`ecs_call_recording` AS `ecs_call_recording`,`ecs`.`ecs_call_return` AS `call_return`,`ecs`.`ecs_transfer` AS `ecs_transfer`,`ecs`.`ecs_call_waiting` AS `ecs_call_waiting`,`ecs`.`ecs_im_status` AS `ecs_im_status` from (`ct_extension_master` `em` left join `ct_extension_call_setting` `ecs` on(`em`.`em_id` = `ecs`.`em_id`)) where `em`.`em_status` = '1' ;");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `tbl_device_templates`, `tbl_device_templates_parameters`, `tbl_devices`, `tbl_device_line_parameter`, `tbl_device_setting`, `tbl_phone_models`, `tbl_template_master`, `tbl_template_details`, `tbl_phone_vendor`;");
    }
}
