<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sip_registrations".
 *
 * @property string $call_id
 * @property string $sip_user
 * @property string $sip_host
 * @property string $presence_hosts
 * @property string $contact
 * @property string $status
 * @property string $ping_status
 * @property int $ping_count
 * @property int $ping_time
 * @property int $force_ping
 * @property string $rpid
 * @property int $expires
 * @property int $ping_expires
 * @property string $user_agent
 * @property string $server_user
 * @property string $server_host
 * @property string $profile_name
 * @property string $hostname
 * @property string $network_ip
 * @property string $network_port
 * @property string $sip_username
 * @property string $sip_realm
 * @property string $mwi_user
 * @property string $mwi_host
 * @property string $orig_server_host
 * @property string $orig_hostname
 * @property string $sub_host
 */
class SipRegistrations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sip_registrations';
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
            //[['contact'], 'string'],
            //[['ping_count', 'ping_time', 'force_ping', 'expires', 'ping_expires'], 'integer'],
            [['call_id', 'sip_user', 'sip_host', 'presence_hosts', 'status', 'ping_status', 'rpid', 'user_agent', 'server_user', 'server_host', 'profile_name', 'hostname', 'network_ip', 'sip_username', 'sip_realm', 'mwi_user', 'mwi_host', 'orig_server_host', 'orig_hostname', 'sub_host'], 'string', 'max' => 255],
            [['network_port'], 'string', 'max' => 6],
            [['sip_user', 'status'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'call_id' => 'Call ID',
            'sip_user' => 'Extension',
            'sip_host' => 'Sip Host',
            'presence_hosts' => 'Presence Hosts',
            'contact' => 'Contact',
            'status' => 'Status',
            'ping_status' => 'Ping Status',
            'ping_count' => 'Ping Count',
            'ping_time' => 'Ping Time',
            'force_ping' => 'Force Ping',
            'rpid' => 'Rpid',
            'expires' => 'Registration Expire Time',
            'ping_expires' => 'Ping Expires',
            'user_agent' => 'User Agent',
            'server_user' => 'Server User',
            'server_host' => 'Server Host',
            'profile_name' => 'Profile Name',
            'hostname' => 'Hostname',
            'network_ip' => 'IP',
            'network_port' => 'Port',
            'sip_username' => 'Sip Username',
            'sip_realm' => 'Sip Realm',
            'mwi_user' => 'Mwi User',
            'mwi_host' => 'Mwi Host',
            'orig_server_host' => 'Orig Server Host',
            'orig_hostname' => 'Orig Hostname',
            'sub_host' => 'Sub Host',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SipRegistrations::find()->where(['sip_registrations.sip_host' => $_SERVER['HTTP_HOST']])/*->groupBy('sip_registrations.sip_user')*/;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if(!empty($this->status)) {
            if($this->status == 'Available'){
                $presence = Yii::$app->fscoredb->createCommand("SELECT `sip_registrations`.sip_user as sip_user FROM `sip_registrations`  WHERE `sip_registrations`.`sip_host`='".$_SERVER['HTTP_HOST']."' and sip_user not in (select sip_user from sip_presence where sip_host = sip_host and sip_user = sip_registrations.sip_user)")->queryAll();
                if (!empty($presence)) {
                    $sipUser = array_column($presence, 'sip_user');
                    $query->andFilterWhere(['IN', 'sip_registrations.sip_user', $sipUser]);
                }else{
                    $query->andWhere(['sip_registrations.sip_user' => '']);
                }

            }else {
                $presence = SipPresence::find()->select('sip_user')->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => $this->status])->asArray()->all();
                if (!empty($presence)) {
                    $sipUser = array_column($presence, 'sip_user');
                    $query->andFilterWhere(['IN', 'sip_registrations.sip_user', $sipUser]);
                }else{
                    $query->andWhere(['sip_registrations.sip_user' => '']);
                }
            }
        }
        if(!empty($this->sip_user)){
            $query->andFilterWhere(['like', 'sip_registrations.sip_user', $this->sip_user.'%', false]);
        }


        return $dataProvider;
    }

}
