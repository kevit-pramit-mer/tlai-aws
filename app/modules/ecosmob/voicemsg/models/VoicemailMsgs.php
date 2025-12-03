<?php

namespace app\modules\ecosmob\voicemsg\models;

use Yii;
use app\modules\ecosmob\voicemsg\VoiceMsgModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "voicemail_msgs".
 *
 * @property int $created_epoch
 * @property int $read_epoch
 * @property int $trash_epoch Trash epoch
 * @property string $username
 * @property int $sip_id
 * @property string $domain
 * @property string $uuid
 * @property string $cid_name
 * @property string $cid_number
 * @property string $in_folder
 * @property string $file_path
 * @property int $message_len
 * @property string $flags
 * @property string $read_flags
 * @property string $forwarded_by
 * @property string $status
 */
class VoicemailMsgs extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'voicemail_msgs';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('masterdb');
    }

    public static function primaryKey()
    {
        return ["uuid"];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_epoch', 'read_epoch', 'trash_epoch', 'sip_id', 'message_len'], 'integer'],
            [['username', 'domain', 'uuid', 'cid_name', 'cid_number', 'in_folder', 'file_path', 'flags', 'read_flags', 'forwarded_by'], 'string', 'max' => 255],
            ['status', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_epoch' => VoiceMsgModule::t('voicemsg', 'created_epoch'),
            'read_epoch' => VoiceMsgModule::t('voicemsg', 'read_epoch'),
            'trash_epoch' => VoiceMsgModule::t('voicemsg', 'trash_epoch'),
            'username' => VoiceMsgModule::t('voicemsg', 'callee_id'),
            'sip_id' => VoiceMsgModule::t('voicemsg', 'sip_id'),
            'domain' => VoiceMsgModule::t('voicemsg', 'domain'),
            'uuid' => VoiceMsgModule::t('voicemsg', 'uuid'),
            'cid_name' => VoiceMsgModule::t('voicemsg', 'cid_name'),
            'cid_number' => VoiceMsgModule::t('voicemsg', 'cid_number'),
            'in_folder' => VoiceMsgModule::t('voicemsg', 'in_folder'),
            'file_path' => VoiceMsgModule::t('voicemsg', 'file_path'),
            'message_len' => VoiceMsgModule::t('voicemsg', 'message_len'),
            'flags' => VoiceMsgModule::t('voicemsg', 'flags'),
            'read_flags' => VoiceMsgModule::t('voicemsg', 'read_flags'),
            'forwarded_by' => VoiceMsgModule::t('voicemsg', 'forwarded_by'),
            'status' => VoiceMsgModule::t('voicemsg', 'status'),
        ];
    }
}
