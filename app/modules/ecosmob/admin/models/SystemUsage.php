<?php

namespace app\modules\ecosmob\admin\models;

use Yii;

/**
 * This is the model class for table "system_usage".
 *
 * @property int $sys_id
 * @property string $sys_time_stamp
 * @property double $sys_cpu_usage
 * @property double $sys_disk_used
 * @property double $sys_mem_used
 * @property string $sys_nginx_status
 * @property string $sys_mysql_status
 * @property double $sys_active_calls

 * @property timestamp $sys_last_reboot
 * @property timestamp $sys_server_time
 */
class SystemUsage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dash_system_usage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys_time_stamp', 'sys_last_reboot', 'sys_server_time'], 'safe'],
            [['sys_cpu_usage', 'sys_disk_used', 'sys_mem_used', 'sys_active_calls'], 'required'],
            [['sys_cpu_usage', 'sys_disk_used', 'sys_mem_used', 'sys_active_calls'], 'number'],
            [['sys_nginx_status', 'sys_mysql_status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sys_id' => Yii::t('app', 'Sys ID'),
            'sys_time_stamp' => Yii::t('app', 'Sys Time Stamp'),
            'sys_cpu_usage' => Yii::t('app', 'Sys Cpu Usage'),
            'sys_disk_used' => Yii::t('app', 'Sys Disk Used'),
            'sys_mem_used' => Yii::t('app', 'Sys Mem Used'),
            'sys_nginx_status' => Yii::t('app', 'Sys Nginx Status'),
            'sys_mysql_status' => Yii::t('app', 'Sys Mysql Status'),
            'sys_active_calls' => Yii::t('app', 'Sys Active Calls'),

            'sys_last_reboot' => Yii::t('app', 'Sys Last Reboot'),
            'sys_server_time' => Yii::t('app', 'Sys Server Time'),
        ];
    }
}
