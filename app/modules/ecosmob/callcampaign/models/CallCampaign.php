<?php

namespace app\modules\ecosmob\callcampaign\models;

use app\modules\ecosmob\callcampaign\CallCampaignModule;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_call_campaign".
 *
 * @property int $cmp_id Campaign ID
 * @property string $cmp_name Campaign Name
 * @property string $cmp_type Campaign Type
 * @property int $cmp_caller_id Campaign Caller Id
 * @property string $cmp_timezone timeZone
 * @property string $cmp_status Status
 * @property string $cmp_disposition dispositions
 */
class CallCampaign extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_call_campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cmp_name', 'cmp_caller_id'], 'required'],
            [['cmp_type', 'cmp_status', 'cmp_disposition'], 'string'],
            [['cmp_caller_id'], 'integer'],
            [['cmp_timezone'], 'safe'],
            [['cmp_name'], 'string', 'max' => 211],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cmp_id' => CallCampaignModule::t('app', 'ID'),
            'cmp_name' => CallCampaignModule::t('app', 'Name'),
            'cmp_type' => CallCampaignModule::t('app', 'Type'),
            'cmp_caller_id' => CallCampaignModule::t('app', 'Caller ID'),
            'cmp_timezone' => CallCampaignModule::t('app', 'Timezone'),
            'cmp_status' => CallCampaignModule::t('app', 'Status'),
            'cmp_disposition' => CallCampaignModule::t('app', 'Disposition'),
        ];
    }

    /**
     * @return array
     */
    public static function getTimeZone(){
        $model_time_zone = Timezone::find()->all();
        $time_zone = ArrayHelper::map($model_time_zone, 'tz_zone', 'tz_zone');
         return $time_zone;
    }
}
