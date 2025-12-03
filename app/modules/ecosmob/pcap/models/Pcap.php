<?php

namespace app\modules\ecosmob\pcap\models;

use app\modules\ecosmob\pcap\PcapModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_pcap".
 *
 * @property int $ct_id
 * @property string $ct_start
 * @property string $ct_stop
 * @property string $ct_filename
 * @property string $ct_url
 */
class Pcap extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_pcap';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ct_name', 'filter', 'buffer_size', 'packets_limit'], 'required'],
            [['ct_name'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => PcapModule::t('pcap', 'ct_name_alphanumeric_validation')],
            [['ct_name'], 'unique'],
            [['buffer_size'], 'number', 'min' => 1, 'max' => 100],
            [['packets_limit'], 'number', 'min' => 1, 'max' => 1000000],
            [['ct_start', 'ct_stop', 'ct_filename', 'ct_url', 'ct_status', 'ct_process'], 'safe'],
            [['ct_start', 'ct_stop', 'ct_filename'], 'string', 'max' => 50],
            [['ct_name'], 'string', 'min' => 3, 'max' => 100],
            [['ct_url'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ct_id' => PcapModule::t('pcap', 'id'),
            'ct_start' => PcapModule::t('pcap', 'start'),
            'ct_stop' => PcapModule::t('pcap', 'stop'),
            'ct_process' => PcapModule::t('pcap', 'process'),
            'ct_filename' => PcapModule::t('pcap', 'filename'),
            'ct_url' => PcapModule::t('pcap', 'url'),
            'ct_name' => PcapModule::t('pcap', 'ct_name'),
            'filter' => PcapModule::t('pcap', 'filter'),
            'buffer_size' => PcapModule::t('pcap', 'buffer_size'),
            'packets_limit' => PcapModule::t('pcap', 'packets_limit'),
        ];
    }
}
