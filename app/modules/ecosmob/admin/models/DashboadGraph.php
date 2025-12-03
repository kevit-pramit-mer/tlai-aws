<?php

namespace app\modules\ecosmob\admin\models;

use Yii;

/**
 * This is the model class for table "dashboad_graph".
 *
 * @property int $id
 * @property double $total_hard_disk
 * @property double $usage_hard_disk
 * @property double $total_memory
 * @property double $usage_memory
 * @property double $total_cpu
 * @property double $usage_cpu
 * @property string $nginx
 * @property string $mysql
 */
class DashboadGraph extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dashboad_graph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_hard_disk', 'usage_hard_disk', 'total_memory', 'usage_memory', 'total_cpu', 'usage_cpu'], 'required'],
            [['total_hard_disk', 'usage_hard_disk', 'total_memory', 'usage_memory', 'total_cpu', 'usage_cpu'], 'number'],
            [['nginx', 'mysql'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'total_hard_disk' => Yii::t('app', 'Total Hard Disk'),
            'usage_hard_disk' => Yii::t('app', 'Usage Hard Disk'),
            'total_memory' => Yii::t('app', 'Total Memory'),
            'usage_memory' => Yii::t('app', 'Usage Memory'),
            'total_cpu' => Yii::t('app', 'Total Cpu'),
            'usage_cpu' => Yii::t('app', 'Usage Cpu'),
            'nginx' => Yii::t('app', 'Nginx'),
            'mysql' => Yii::t('app', 'Mysql'),
        ];
    }
}
