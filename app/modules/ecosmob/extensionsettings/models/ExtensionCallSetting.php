<?php

namespace app\modules\ecosmob\extensionsettings\models;

use Yii;

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
 * @property string $ecs_force_record
 * @property string $ecs_moh
 * @property string $ecs_audio_codecs
 * @property string $ecs_video_codecs
 * @property string $ecs_dial_out
 * @property string $ecs_forwarding 0=disable 1=indi forwarding 2= fmfm forwarding
 * @property string $ecs_voicemail
 * @property string $ecs_voicemail_password
 * @property string $ecs_fax2mail
 * @property string $ecs_feature_code_pin
 * @property string $ecs_multiple_registeration
 */
class ExtensionCallSetting extends \yii\db\ActiveRecord
{
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
            [['em_id', 'ecs_max_calls', 'ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_srtp', 'ecs_force_record', 'ecs_moh', 'ecs_audio_codecs', 'ecs_video_codecs', 'ecs_dial_out', 'ecs_voicemail', 'ecs_voicemail_password', 'ecs_fax2mail', 'ecs_feature_code_pin', 'ecs_multiple_registeration'], 'required'],
            [['em_id', 'ecs_max_calls', 'ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_srtp', 'ecs_force_record', 'ecs_moh', 'ecs_audio_codecs', 'ecs_video_codecs', 'ecs_dial_out', 'ecs_voicemail', 'ecs_voicemail_password', 'ecs_fax2mail', 'ecs_feature_code_pin', 'ecs_multiple_registeration'], 'safe'],
            [['em_id', 'ecs_max_calls', 'ecs_ring_timeout', 'ecs_call_timeout', 'ecs_ob_max_timeout'], 'integer'],
            [['ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_bypass_media', 'ecs_srtp', 'ecs_force_record', 'ecs_dial_out', 'ecs_forwarding', 'ecs_voicemail', 'ecs_fax2mail', 'ecs_multiple_registeration'], 'string'],
            [['ecs_moh', 'ecs_audio_codecs', 'ecs_video_codecs'], 'string', 'max' => 100],
            [['ecs_feature_code_pin'], 'string', 'max' => 32],
            //[['ecs_voicemail_password'], 'integer','min'=>3, 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ecs_id' => Yii::t('app', 'Ecs ID'),
            'em_id' => Yii::t('app', 'Em ID'),
            'ecs_max_calls' => Yii::t('app', 'Ecs Max Calls'),
            'ecs_ring_timeout' => Yii::t('app', 'Ecs Ring Timeout'),
            'ecs_call_timeout' => Yii::t('app', 'Ecs Call Timeout'),
            'ecs_ob_max_timeout' => Yii::t('app', 'Ecs Ob Max Timeout'),
            'ecs_auto_recording' => Yii::t('app', 'Ecs Auto Recording'),
            'ecs_dtmf_type' => Yii::t('app', 'Ecs Dtmf Type'),
            'ecs_video_calling' => Yii::t('app', 'Ecs Video Calling'),
            'ecs_bypass_media' => Yii::t('app', 'Ecs Bypass Media'),
            'ecs_srtp' => Yii::t('app', 'Ecs Srtp'),
            'ecs_force_record' => Yii::t('app', 'Ecs Force Record'),
            'ecs_moh' => Yii::t('app', 'Ecs Moh'),
            'ecs_audio_codecs' => Yii::t('app', 'Ecs Audio Codecs'),
            'ecs_video_codecs' => Yii::t('app', 'Ecs Video Codecs'),
            'ecs_dial_out' => Yii::t('app', 'Ecs Dial Out'),
            'ecs_forwarding' => Yii::t('app', 'Ecs Forwarding'),
            'ecs_voicemail' => Yii::t('app', 'Ecs Voicemail'),
            'ecs_voicemail_password' => Yii::t('app', 'Ecs Voicemail Password'),
            'ecs_fax2mail' => Yii::t('app', 'Ecs Fax2mail'),
            'ecs_feature_code_pin' => Yii::t('app', 'Ecs Feature Code Pin'),
            'ecs_multiple_registeration' => Yii::t('app', 'Ecs Multiple Registeration'),
        ];
    }
}
