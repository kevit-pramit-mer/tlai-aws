<?php

namespace app\modules\ecosmob\admin\models;

use app\modules\ecosmob\admin\AdminModule;
use Yii;

/**
 * This is the model class for table "system_load".
 *
 * @property int $sys_id
 * @property string $sys_time_stamp
 * @property double $sys_cpu_usage
 * @property double $sys_cpu_system
 * @property double $sys_cpu_nice
 * @property double $sys_cpu_io_wait
 * @property double $sys_mem_used
 * @property double $sys_mem_free
 * @property int $sys_disk_used
 * @property int $sys_disk_free
 * @property double $sys_load_1
 * @property double $sys_load_5
 * @property double $sys_load_15
 */
class SystemLoad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_load';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'sys_cpu_usage',
                    'sys_cpu_system',
                    'sys_cpu_nice',
                    'sys_cpu_io_wait',
                    'sys_mem_used',
                    'sys_mem_free',
                    'sys_load_1',
                    'sys_load_5',
                    'sys_load_15'
                ],
                'number'
            ],
            [['sys_disk_used', 'sys_disk_free'], 'integer'],
            [
                [
                    'sys_time_stamp',
                    'sys_cpu_usage',
                    'sys_cpu_system',
                    'sys_cpu_nice',
                    'sys_cpu_io_wait',
                    'sys_mem_used',
                    'sys_mem_free',
                    'sys_disk_used',
                    'sys_disk_free',
                    'sys_load_1',
                    'sys_load_5',
                    'sys_load_15'
                ],
                'safe'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sys_id' => AdminModule::t('admin', 'sys_id'),
            'sys_time_stamp' => AdminModule::t('admin', 'sys_time_stamp'),
            'sys_cpu_usage' => AdminModule::t('admin', 'sys_cpu_usage'),
            'sys_cpu_system' => AdminModule::t('admin', 'sys_cpu_system'),
            'sys_cpu_nice' => AdminModule::t('admin', 'sys_cpu_nice'),
            'sys_cpu_io_wait' => AdminModule::t('admin', 'sys_cpu_io_wait'),
            'sys_mem_used' => AdminModule::t('admin', 'sys_mem_used'),
            'sys_mem_free' => AdminModule::t('admin', 'sys_mem_free'),
            'sys_disk_used' => AdminModule::t('admin', 'sys_disk_used'),
            'sys_disk_free' => AdminModule::t('admin', 'sys_disk_free'),
            'sys_load_1' => AdminModule::t('admin', 'sys_load_1'),
            'sys_load_5' => AdminModule::t('admin', 'sys_load_5'),
            'sys_load_15' => AdminModule::t('admin', 'sys_load_15'),
        ];
    }
}
