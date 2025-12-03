<?php

namespace app\modules\ecosmob\customerdetails\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\customerdetails\CustomerDetailsModule;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "campaign_mapping_user".
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $supervisor_id
 *
 * @property Campaign $campaign
 */
class CampaignMappingUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_mapping_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'supervisor_id'], 'required'],
            [['campaign_id'], 'integer'],
            ['lg_first_name', 'safe'],
            [['supervisor_id'], 'string', 'max' => 200],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'cmp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'supervisor_id' => Yii::t('app', 'Supervisor ID'),
            'lg_first_name' => CustomerDetailsModule::t('customerdetails', 'lg_first_name'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['cmp_id' => 'campaign_id']);
    }
}
