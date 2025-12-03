<?php

namespace app\modules\ecosmob\iptable\models;

use app\modules\ecosmob\iptable\IpTableModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_ip_table".
 *
 * @property int $it_id
 * @property string $it_source
 * @property string $it_destination
 * @property int $it_port
 * @property string $it_protocol
 * @property string $it_service
 * @property string $it_action
 * @property string $it_direction
 */
class IpTable extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_ip_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['it_source', 'it_destination'], 'ip'],
            [['it_source', 'it_destination', 'it_port', 'it_protocol', 'it_service', 'it_action', 'it_direction'], 'required'],
            [['it_source', 'it_destination', 'it_port', 'it_protocol', 'it_service', 'it_action', 'it_direction'], 'safe'],
            [['it_port'], 'integer', 'min' => 1, 'max' => 65536],
            [['it_port'], 'string', 'min' => 1, 'max' => 5],
            [['it_protocol', 'it_action', 'it_direction'], 'string'],
            [['it_source', 'it_destination', 'it_service'], 'string', 'max' => 50],
            [
                'it_destination',
                'compare',
                'compareAttribute' => 'it_source',
                'operator' => '!=',
                'message' => IpTableModule::t('it', 'destination_must_be_different_from_source')
            ],

            [
                ['it_source', 'it_port'],
                'unique',
                'targetAttribute' => ['it_source', 'it_port'],
                'when' => function ($model) {

                    $trunkModel = IpTable::find()->where(
                        [
                            'it_source' => $this->it_source,
                            'it_port' => $this->it_port,
                        ]
                    )->count();

                    if ($trunkModel > 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                },

            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'it_id' => IpTableModule::t('it', 'id'),
            'it_source' => IpTableModule::t('it', 'source'),
            'it_destination' => IpTableModule::t('it', 'destination'),
            'it_port' => IpTableModule::t('it', 'port'),
            'it_protocol' => IpTableModule::t('it', 'protocol'),
            'it_service' => IpTableModule::t('it', 'service'),
            'it_action' => IpTableModule::t('it', 'action'),
            'it_direction' => IpTableModule::t('it', 'direction'),
            'it_inbound' => IpTableModule::t('it', 'inbound'),
            'it_outbound' => IpTableModule::t('it', 'outbound'),
            'it_accept' => IpTableModule::t('it', 'accept'),
            'it_reject' => IpTableModule::t('it', 'reject'),
            'it_tcp' => IpTableModule::t('it', 'tcp'),
            'it_udp' => IpTableModule::t('it', 'udp'),
            'it_any' => IpTableModule::t('it', 'any'),
        ];
    }
}
