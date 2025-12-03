<?php

namespace app\modules\ecosmob\campaign\models;

use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\campaign\CampaignModule;
use app\modules\ecosmob\disposition\models\DispositionMaster;
use app\modules\ecosmob\jobs\models\Job;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use app\modules\ecosmob\timezone\models\Timezone;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ct_call_campaign".
 *
 * @property int $cmp_id Campaign ID
 * @property string $cmp_name Campaign Name
 * @property string $cmp_type Campaign Type
 * @property int $cmp_caller_id Campaign Caller ID
 * @property string $cmp_disposition dispositions
 * @property string $cmp_timezone timeZone
 * @property string $cmp_status Status
 * @property string $cmp_dialer_type
 */
class Campaign extends ActiveRecord
{
    public $agents;
    public $supervisors;


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
            [['cmp_name', 'cmp_type', 'cmp_status', 'cmp_timezone', 'cmp_lead_group', 'cmp_script', 'cmp_disposition', 'agents', 'supervisors'], 'required'],
            [['cmp_name'], 'unique'],
            [['cmp_description', 'amd_detect', 'abandoned_call_try', 'dial_ration', 'agents', 'supervisors'], 'safe'],
            ['cmp_name', 'match', 'pattern' => '/^[a-zA-Z][0-9a-zA-Z., ]+$/', 'message' => CampaignModule::t('campaign', 'camp_name_can_contain_alphanumeric_and_spaces')],
            // [['cmp_caller_name', 'cmp_caller_id'], 'checkrequire'],
            ['cmp_caller_name', 'match', 'pattern' => '/^[a-zA-Z][a-zA-Z., ]+$/', 'message' => CampaignModule::t('campaign', 'caller_name_can_contain_alphabate_and_spaces')],
            [['cmp_status'], 'string'],
            [['cmp_name'], 'string', 'max' => 40],
            [['cmp_description'], 'string', 'max' => 150],
            [['cmp_caller_name'], 'string', 'max' => 30],
            [['cmp_caller_id'], 'string','min'=>1,'max'=>15],
            [
                'cmp_caller_id',
                'match',
                'pattern' => '/^[0-9+]{0,20}$/',
                'message' => CampaignModule::t('campaign', 'caller_id_must_be'),
            ],
            [['cmp_dialer_type', 'cmp_caller_name', 'cmp_disposition', 'cmp_timezone', 'cmp_week_off_type', 'cmp_week_off_name', 'cmp_holiday_type', 'cmp_holiday_name', 'cmp_lead_group', 'cmp_trunk', 'cmp_script', 'cmp_queue_id', 'cmp_type'], 'safe'],
            [
                'cmp_queue_id',
                'required',
                'when' => function ($model) {
                    return ($model->cmp_type != 'Outbound');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type :selected').val() != 'Outbound');
                    }"
            ],
            [
                ['cmp_dialer_type', 'cmp_caller_name', 'cmp_caller_id'],
                'required',
                'when' => function ($model) {
                    return ($model->cmp_type == 'Outbound');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type').val() == 'Outbound');
                    }"
            ],
            [
                ['cmp_caller_name', 'cmp_caller_id'],
                'required',
                'when' => function ($model) {
                    return ($model->cmp_type == 'Blended');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type').val() == 'Blended');
                    }"
            ],

            [
                ['cmp_caller_name', 'cmp_caller_id'],
                'unique',
                'when' => function ($model) {
                    return ($model->cmp_type == 'Blended' || $model->cmp_type == 'Outbound');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type').val() == 'Blended' || $('#campaign-cmp_type').val() == 'Outbound');
                    }"
            ],

            [
                'cmp_week_off_name',
                'required',
                'when' => function ($model) {
                    return ($model->cmp_type == 'Inbound' && $model->cmp_week_off_type != '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type').val() == 'Inbound' && $('#campaign-cmp_week_off_type').val() != '');
                    }",
                'message' => "This field is required."
            ],
            [
                'cmp_holiday_name',
                'required',
                'when' => function ($model) {
                    return ($model->cmp_type == 'Inbound' && $model->cmp_holiday_type != '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#campaign-cmp_type').val() == 'Inbound' && $('#campaign-cmp_holiday_type').val() != '');
                    }",
                'message' => "This field is required."
            ],
            [['cmp_status'], 'checklog'],
            ['cmp_lead_group', 'checkLeadGroup']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cmp_name' => CampaignModule::t('campaign', 'cmp_name'),
            'cmp_type' => CampaignModule::t('campaign', 'cmp_type'),
            'cmp_caller_id' => CampaignModule::t('campaign', 'cmp_caller_id'),
            'cmp_caller_name' => CampaignModule::t('campaign', 'cmp_caller_name'),
            'cmp_disposition' => CampaignModule::t('campaign', 'cmp_disposition'),
            'cmp_timezone' => CampaignModule::t('campaign', 'cmp_timezone'),
            'cmp_status' => CampaignModule::t('campaign', 'cmp_status'),
            'cmp_dialer_type' => CampaignModule::t('campaign', 'cmp_dialer_type'),
            'cmp_lead_group' => CampaignModule::t('campaign', 'cmp_lead_group'),
            'cmp_script' => CampaignModule::t('campaign', 'cmp_script'),
            'cmp_queue_id' => CampaignModule::t('campaign', 'cmp_queue_id'),
            'cmp_week_off_name' => CampaignModule::t('campaign', 'WO_name'),
            'cmp_holiday_name' => CampaignModule::t('campaign', 'holiday_name'),
            'cmp_description' => CampaignModule::t('campaign', 'cmp_description'),

        ];
    }

    public function canDelete($id)
    {
        /** @var Job $jobCount */
        $jobCount = Job::find()->where(['campaign_id' => $id])->count();
        $campCdr = CampCdr::find()->andWhere(new Expression('FIND_IN_SET("' . $id . '", camp_name)'))->count();
        if ($jobCount == 0 && $campCdr == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getTimezone()
    {
        return $this->hasOne(Timezone::className(), ['tz_id' => 'cmp_timezone']);

    }

    public function getDisposition()
    {
        return $this->hasOne(DispositionMaster::className(), ['ds_id' => 'cmp_disposition']);

    }
    public function checklog($attribute){
        if($this->cmp_status == 'Inactive'){
            $userLog = UsersActivityLog::find()->where(['DATE(login_time)' => date('Y-m-d'), 'logout_time' => '0000-00-00 00:00:00'])->orderBy(['login_time' => SORT_DESC])->count();
            if($userLog > 0){
                $this->addError($attribute, CampaignModule::t('campaign', 'status_error'));
            }
        }
    }

    public function checkLeadGroup($attribute){
        if(!empty($this->cmp_type) && !empty($this->cmp_lead_group)) {
            if ($this->cmp_type == 'Inbound') {
                $campaign = Campaign::find()->andWhere(['cmp_lead_group' => $this->cmp_lead_group, 'cmp_type' => 'Outbound', 'cmp_dialer_type' => 'PREVIEW']);
            }
            if ($this->cmp_type == 'Outbound') {
                $campaign = Campaign::find()->andWhere(['cmp_lead_group' => $this->cmp_lead_group, 'cmp_type' => 'Inbound']);
            }
            if (!empty($this->cmp_id)) {
                $campaign = $campaign->andWhere(['!=', 'cmp_id', $this->cmp_id]);
            }
            $campaign = $campaign->count();
            if($campaign > 0){
                $this->addError($attribute, CampaignModule::t('campaign', 'invalidLeadGroupAssignment'));
            }
        }
    }

    public function getQueue()
    {
        return $this->hasOne(QueueMaster::className(), ['qm_id' => 'cmp_queue_id']);

    }
}
