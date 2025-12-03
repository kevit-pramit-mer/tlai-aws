<?php

namespace app\models;

use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "channels".
 *
 * @property string $uuid
 * @property string $direction
 * @property string $created
 * @property int $created_epoch
 * @property string $name
 * @property string $state
 * @property string $cid_name
 * @property string $cid_num
 * @property string $ip_addr
 * @property string $dest
 * @property string $application
 * @property string $application_data
 * @property string $dialplan
 * @property string $context
 * @property string $read_codec
 * @property string $read_rate
 * @property string $read_bit_rate
 * @property string $write_codec
 * @property string $write_rate
 * @property string $write_bit_rate
 * @property string $secure
 * @property string $hostname
 * @property string $presence_id
 * @property string $presence_data
 * @property string $accountcode
 * @property string $callstate
 * @property string $callee_name
 * @property string $callee_num
 * @property string $callee_direction
 * @property string $call_uuid
 * @property string $sent_callee_name
 * @property string $sent_callee_num
 * @property string $initial_cid_name
 * @property string $initial_cid_num
 * @property string $initial_ip_addr
 * @property string $initial_dest
 * @property string $initial_dialplan
 * @property string $initial_context
 */
class Channels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channels';
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
            //[['created_epoch'], 'integer'],
            [['name', 'cid_name', 'application_data', 'presence_id', 'presence_data', 'callee_name', 'sent_callee_name', 'initial_cid_name', 'initial_dest'], 'string'],
            [['uuid', 'ip_addr', 'hostname', 'accountcode', 'callee_num', 'call_uuid', 'sent_callee_num', 'initial_cid_num', 'initial_ip_addr'], 'string', 'max' => 255],
            [['direction', 'read_rate', 'read_bit_rate', 'write_rate', 'write_bit_rate'], 'string', 'max' => 32],
            [['created', 'application', 'dialplan', 'context', 'read_codec', 'write_codec', 'initial_dialplan', 'initial_context'], 'string', 'max' => 128],
            [['state', 'secure'], 'string', 'max' => 64],
            [['callee_direction'], 'string', 'max' => 5],
            [['cid_num', 'dest', 'callstate'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uuid' => 'Uuid',
            'direction' => 'Direction',
            'created' => 'Created',
            'created_epoch' => 'Call Duration',
            'name' => 'Name',
            'state' => 'State',
            'cid_name' => 'Cid Name',
            'cid_num' => 'Caller',
            'ip_addr' => 'IP',
            'dest' => 'Callee',
            'application' => 'Application',
            'application_data' => 'Application Data',
            'dialplan' => 'Dialplan',
            'context' => 'Context',
            'read_codec' => 'Read Codec',
            'read_rate' => 'Read Rate',
            'read_bit_rate' => 'Read Bit Rate',
            'write_codec' => 'Write Codec',
            'write_rate' => 'Write Rate',
            'write_bit_rate' => 'Write Bit Rate',
            'secure' => 'Secure',
            'hostname' => 'Hostname',
            'presence_id' => 'Presence ID',
            'presence_data' => 'Presence Data',
            'accountcode' => 'Accountcode',
            'callstate' => 'Status',
            'callee_name' => 'Callee Name',
            'callee_num' => 'Callee Num',
            'callee_direction' => 'Callee Direction',
            'call_uuid' => 'Call Uuid',
            'sent_callee_name' => 'Sent Callee Name',
            'sent_callee_num' => 'Sent Callee Num',
            'initial_cid_name' => 'Initial Cid Name',
            'initial_cid_num' => 'Initial Cid Num',
            'initial_ip_addr' => 'To Trunk',
            'initial_dest' => 'Initial Dest',
            'initial_dialplan' => 'Initial Dialplan',
            'initial_context' => 'Initial Context',
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
        $did = DidManagement::find()->select(['did_number'])->asArray()->all();
        $didNumber = array_column($did, 'did_number');
        $channels = Channels::find()->select(['uuid'])->andWhere(['OR', ['like', 'presence_id', $_SERVER['HTTP_HOST']], ['IN', 'dest', $didNumber]])->asArray()->all();
        $uuid = array_column($channels, 'uuid');
        $query = Channels::find()->andWhere(['OR',
            ['like', 'presence_id', $_SERVER['HTTP_HOST']],
            ['IN', 'call_uuid', $uuid]
        ])->andWhere(['NOT IN', 'dest', $didNumber])->groupBy('dest');

       /* $query = Channels::find()->andWhere(['like', 'presence_id', $_SERVER['HTTP_HOST']])
            ->groupBy('call_uuid');*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'cid_num' => $this->cid_num,
            'dest' => $this->dest,
            'ip_addr' => $this->ip_addr,
            'initial_ip_addr' => $this->initial_ip_addr,
            'callstate' => $this->callstate
        ]);

        return $dataProvider;
    }
}
