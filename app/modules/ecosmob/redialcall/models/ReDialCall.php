<?php

namespace app\modules\ecosmob\redialcall\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_leadgroup_master".
 *
 * @property int $ld_id lead group auto increment id
 * @property string $ld_group_name lead group name
 */
class ReDialCall extends ActiveRecord
{
    public $disposition;
    public $campaign;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_leadgroup_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ld_group_name', 'disposition', 'campaign'], 'safe'],
            [['disposition', 'ld_group_name', 'campaign'], 'required'],
            [['ld_group_name'], 'string', 'max' => 211],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ld_id' => Yii::t('app', 'Lead Id'),
            'ld_group_name' => Yii::t('app', 'lead_group'),
        ];
    }
}
