<?php

namespace app\modules\ecosmob\extension\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_extension_call_setting".
 *
 * @property int $ecs_id
 * @property int $em_id
 * @property int $ecs_max_calls
 * @property int $ecs_ring_timeout
 * @property int $ecs_call_timeout
 * @property int $ecs_ob_max_timeout
 * @property string $ecs_auto_recording
 * @property string $ecs_dtmf_type
 * @property string $ecs_video_calling
 * @property string $ecs_bypass_media
 * @property string $ecs_srtp
 * @property string $ecs_forwarding
 * @property string $ecs_force_record
 * @property string $ecs_moh
 * @property string $ecs_audio_codecs
 * @property string $ecs_video_codecs
 * @property string $ecs_dial_out
 * @property string $ecs_voicemail
 * @property string $ecs_voicemail_password
 * @property string $ecs_fax2mail
 * @property string $ecs_feature_code_pin
 * @property string $ecs_multiple_registeration
 * @property string $ecs_blacklist
 * @property string $ecs_call_redial
 * @property string $ecs_bargein
 * @property string $ecs_busy_call_back
 * @property string $ecs_park
 * @property string $ecs_do_not_disturb
 * @property string $ecs_caller_id_block
 * @property string $ecs_whitelist
 * @property string $ecs_call_recording
 * @property string $ecs_call_return
 * @property string $ecs_transfer
 * @property string $ecs_call_waiting
 * @property string $ecs_accept_blocked_caller_id
 * @property string $ecs_im_status
 */
class Callsettings extends ActiveRecord
{
    public $audio_codecs = [],$video_codecs = [];
    public $all_audio_codec;
    public $all_video_codec;
    public $orig_codec;
    public $orig_video_codec;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_extension_call_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['em_id', 'ecs_max_calls', /*'ecs_feature_code_pin' ,*/'ecs_forwarding', 'ecs_call_timeout'], 'required'],
            [['em_id', 'ecs_voicemail_password'], 'integer'],
            [['ecs_max_calls'], 'number'],
            [['ecs_max_calls'], 'string', 'min' => 1, 'max' => 3, 'tooLong'=> Yii::t('app', 'max_calls_max_validation'), 'tooShort' => Yii::t('app', 'max_calls_min_validation')],
            [['ecs_max_calls'], 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => Yii::t('app', 'max_calls_greater_than_zero')],

                //[['ecs_voicemail_password'], 'integer', 'min' => 4],
            [
                'ecs_voicemail_password',
                'match',
                'pattern' => '/^[0-9+]{4,15}$/',
                'message' => Yii::t('app', 'voice_mail_password_validation'),
            ],
            [
                'ecs_feature_code_pin',
                'match',
                'pattern' => '/^[0-9+]{4,15}$/',
                'message' => Yii::t('app', 'fearturecode_pin_validation'),
            ],
            [['ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_bypass_media', 'ecs_dial_out', 'ecs_voicemail', 'ecs_fax2mail', 'ecs_multiple_registeration'], 'string'],
            [['ecs_ob_max_timeout'], 'integer', 'min' => 10, 'max' => 9999],
            [['ecs_ob_max_timeout'], 'string', 'min' => 2, 'max' => 4],
            [['ecs_ring_timeout', 'ecs_call_timeout'], 'integer', 'min' => 10, 'max' => 240],
            [['ecs_ring_timeout', 'ecs_call_timeout'], 'string', 'min' => 2, 'max' => 3],
            [['ecs_audio_codecs', 'ecs_video_codecs'], 'string', 'max' => 100],
            [['ecs_forwarding'], 'string', 'max' => 32],
            [['ecs_ring_timeout', 'ecs_call_timeout', 'ecs_ob_max_timeout', 'ecs_voicemail', 'ecs_blacklist', 'ecs_accept_blocked_caller_id', 'ecs_call_redial', 'ecs_bargein', 'ecs_park', 'ecs_busy_call_back', 'ecs_do_not_disturb', 'ecs_whitelist', 'ecs_caller_id_block', 'ecs_call_recording', 'ecs_call_return', 'ecs_transfer', 'ecs_call_waiting', 'ecs_im_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ecs_max_calls' => Yii::t('app', 'simultaneous_external_call'),
            'ecs_ring_timeout' => Yii::t('app', 'ecs_ring_timeout'),
            'ecs_call_timeout' => Yii::t('app', 'ecs_call_timeout'),
            'ecs_ob_max_timeout' => Yii::t('app', 'ecs_ob_max_timeout'),
            'ecs_auto_recording' => Yii::t('app', 'extension_auto_recording'),
            'ecs_dtmf_type' => Yii::t('app', 'DTMF_type'),
            'ecs_video_calling' => Yii::t('app', 'video_calling'),
            'ecs_bypass_media' => Yii::t('app', 'Bypass Media'),
            'ecs_audio_codecs' => Yii::t('app', 'Audio Codecs'),
            'ecs_video_codecs' => Yii::t('app', 'Video Codecs'),
            'ecs_dial_out' => Yii::t('app', 'dial_out'),
            'ecs_voicemail' => Yii::t('app', 'voice_mail'),
            'ecs_voicemail_password' => Yii::t('app', 'vm_password'),
            'ecs_fax2mail' => Yii::t('app', 'fax'),
            'ecs_feature_code_pin' => Yii::t('app', 'feature_code_pin'),
            'ecs_multiple_registeration' => Yii::t('app', 'multiple_registeration'),
            'orig_codec' => Yii::t('app', 'multiple_registeration'),
            'ecs_forwarding' => Yii::t('app', 'ecs_forwarding_value'),
            'ecs_blacklist' => Yii::t('app', 'black_list List'),
            'ecs_accept_blocked_caller_id' => Yii::t('app', 'accept_blocked_caller_id'),
            'ecs_call_redial' => Yii::t('app', 'call_redial'),
            'ecs_bargein' => Yii::t('app', 'bargein'),
            'ecs_park' => Yii::t('app', 'park'),
            'ecs_busy_call_back' => Yii::t('app', 'busy_call_back'),
            'ecs_do_not_disturb' => Yii::t('app', 'do_not_disturb'),
            'ecs_whitelist' => Yii::t('app', 'white_list'),
            'ecs_caller_id_block' => Yii::t('app', 'caller_id_block'),
            'ecs_call_recording' => Yii::t('app', 'call_recording'),
            'ecs_call_return' => Yii::t('app', 'call_return'),
            'ecs_transfer' => Yii::t('app', 'call_transfer'),
            'ecs_call_waiting' => Yii::t('app', 'call_waiting'),

        ];
    }
}
