<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sip_presence".
 *
 * @property string $sip_user
 * @property string $sip_host
 * @property string $status
 * @property string $rpid
 * @property int $expires
 * @property string $user_agent
 * @property string $profile_name
 * @property string $hostname
 * @property string $network_ip
 * @property string $network_port
 * @property string $open_closed
 */
class SipPresence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sip_presence';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('fscoredb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expires'], 'integer'],
            [['sip_user', 'sip_host', 'status', 'rpid', 'user_agent', 'profile_name', 'hostname', 'network_ip', 'open_closed'], 'string', 'max' => 255],
            [['network_port'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sip_user' => 'Extension',
            'sip_host' => 'Sip Host',
            'status' => 'Status',
            'rpid' => 'Rpid',
            'expires' => 'Registration Expire Time',
            'user_agent' => 'User Agent',
            'profile_name' => 'Profile Name',
            'hostname' => 'Hostname',
            'network_ip' => 'IP',
            'network_port' => 'Port',
            'open_closed' => 'Open Closed',
        ];
    }
    public static function getStatus($sipHost, $sipUser)
    {
        return SipPresence::find()->select('status')->where(['sip_host' => $sipHost, 'sip_user' => $sipUser])->one();
    }
}
