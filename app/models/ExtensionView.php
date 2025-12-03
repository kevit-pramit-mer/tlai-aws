<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extension_view".
 *
 * @property int $em_id
 * @property string $em_extension_name
 * @property string $em_extension_number
 * @property string $em_password
 * @property int $em_plan_id
 * @property string $em_web_password It is used for login to the web portal.
 * @property string $em_status
 * @property int $em_shift_id
 * @property int $em_group_id
 * @property int $em_language_id
 * @property string $em_email
 * @property int $em_timezone_id
 * @property string $is_phonebook
 * @property string $em_moh
 * @property string $em_token
 * @property int $trago_user_id
 * @property string $is_tragofone
 * @property string $external_caller_id
 * @property string $trago_username
 * @property int $ecs_id
 * @property int $ecs_max_calls
 * @property int $ecs_ring_timeout
 * @property int $ecs_call_timeout
 * @property int $ecs_ob_max_timeout
 * @property string $ecs_auto_recording
 * @property string $ecs_dtmf_type
 * @property string $ecs_video_calling
 * @property string $ecs_bypass_media
 * @property string $ecs_srtp
 * @property string $ecs_force_record
 * @property string $ecs_moh
 * @property string $ecs_audio_codecs
 * @property string $ecs_video_codecs
 * @property string $ecs_dial_out
 * @property string $ecs_forwarding 0=disable 1=indi forwarding 2= fmfm forwarding 3=enable
 * @property string $ecs_voicemail
 * @property string $ecs_voicemail_password
 * @property string $ecs_fax2mail
 * @property string $ecs_feature_code_pin
 * @property string $ecs_multiple_registeration
 * @property string $ecs_blacklist
 * @property string $ecs_accept_blocked_caller_id
 * @property string $ecs_call_redial
 * @property string $ecs_bargein
 * @property string $ecs_park
 * @property string $ecs_busy_call_back
 * @property string $ecs_do_not_disturb
 * @property string $ecs_whitelist
 * @property string $ecs_caller_id_block
 * @property string $ecs_call_recording
 * @property string $call_return
 * @property string $ecs_transfer
 * @property string $ecs_call_waiting
 * @property string $ecs_im_status
 */
class ExtensionView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extension_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['em_id', 'em_plan_id', 'em_shift_id', 'em_group_id', 'em_language_id', 'em_timezone_id', 'trago_user_id', 'ecs_id', 'ecs_max_calls', 'ecs_ring_timeout', 'ecs_call_timeout', 'ecs_ob_max_timeout'], 'integer'],
            [['em_extension_name', 'em_extension_number', 'em_password', 'em_plan_id', 'em_web_password', 'em_status', 'em_shift_id', 'em_group_id', 'em_language_id', 'em_email', 'em_timezone_id', 'em_moh'], 'required'],
            [['em_status', 'is_phonebook', 'is_tragofone', 'ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_bypass_media', 'ecs_srtp', 'ecs_force_record', 'ecs_dial_out', 'ecs_forwarding', 'ecs_voicemail', 'ecs_fax2mail', 'ecs_multiple_registeration', 'ecs_blacklist', 'ecs_accept_blocked_caller_id', 'ecs_call_redial', 'ecs_bargein', 'ecs_park', 'ecs_busy_call_back', 'ecs_do_not_disturb', 'ecs_whitelist', 'ecs_caller_id_block', 'ecs_call_recording', 'call_return', 'ecs_transfer', 'ecs_call_waiting', 'ecs_im_status'], 'string'],
            [['em_extension_name'], 'string', 'max' => 200],
            [['em_extension_number', 'em_token', 'external_caller_id', 'trago_username', 'ecs_audio_codecs', 'ecs_video_codecs', 'ecs_voicemail_password'], 'string', 'max' => 100],
            [['em_password', 'ecs_feature_code_pin'], 'string', 'max' => 32],
            [['em_web_password'], 'string', 'max' => 255],
            [['em_email'], 'string', 'max' => 256],
            [['em_moh', 'ecs_moh'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'em_id' => 'Em ID',
            'em_extension_name' => 'Em Extension Name',
            'em_extension_number' => 'Em Extension Number',
            'em_password' => 'Em Password',
            'em_plan_id' => 'Em Plan ID',
            'em_web_password' => 'Em Web Password',
            'em_status' => 'Em Status',
            'em_shift_id' => 'Em Shift ID',
            'em_group_id' => 'Em Group ID',
            'em_language_id' => 'Em Language ID',
            'em_email' => 'Em Email',
            'em_timezone_id' => 'Em Timezone ID',
            'is_phonebook' => 'Is Phonebook',
            'em_moh' => 'Em Moh',
            'em_token' => 'Em Token',
            'trago_user_id' => 'Trago User ID',
            'is_tragofone' => 'Is Tragofone',
            'external_caller_id' => 'External Caller ID',
            'trago_username' => 'Trago Username',
            'ecs_id' => 'Ecs ID',
            'ecs_max_calls' => 'Ecs Max Calls',
            'ecs_ring_timeout' => 'Ecs Ring Timeout',
            'ecs_call_timeout' => 'Ecs Call Timeout',
            'ecs_ob_max_timeout' => 'Ecs Ob Max Timeout',
            'ecs_auto_recording' => 'Ecs Auto Recording',
            'ecs_dtmf_type' => 'Ecs Dtmf Type',
            'ecs_video_calling' => 'Ecs Video Calling',
            'ecs_bypass_media' => 'Ecs Bypass Media',
            'ecs_srtp' => 'Ecs Srtp',
            'ecs_force_record' => 'Ecs Force Record',
            'ecs_moh' => 'Ecs Moh',
            'ecs_audio_codecs' => 'Ecs Audio Codecs',
            'ecs_video_codecs' => 'Ecs Video Codecs',
            'ecs_dial_out' => 'Ecs Dial Out',
            'ecs_forwarding' => 'Ecs Forwarding',
            'ecs_voicemail' => 'Ecs Voicemail',
            'ecs_voicemail_password' => 'Ecs Voicemail Password',
            'ecs_fax2mail' => 'Ecs Fax2mail',
            'ecs_feature_code_pin' => 'Ecs Feature Code Pin',
            'ecs_multiple_registeration' => 'Ecs Multiple Registeration',
            'ecs_blacklist' => 'Ecs Blacklist',
            'ecs_accept_blocked_caller_id' => 'Ecs Accept Blocked Caller ID',
            'ecs_call_redial' => 'Ecs Call Redial',
            'ecs_bargein' => 'Ecs Bargein',
            'ecs_park' => 'Ecs Park',
            'ecs_busy_call_back' => 'Ecs Busy Call Back',
            'ecs_do_not_disturb' => 'Ecs Do Not Disturb',
            'ecs_whitelist' => 'Ecs Whitelist',
            'ecs_caller_id_block' => 'Ecs Caller Id Block',
            'ecs_call_recording' => 'Ecs Call Recording',
            'call_return' => 'Call Return',
            'ecs_transfer' => 'Ecs Transfer',
            'ecs_call_waiting' => 'Ecs Call Waiting',
            'ecs_im_status' => 'Ecs Im Status',
        ];
    }
}
