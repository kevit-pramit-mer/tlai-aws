<?php

namespace app\modules\ecosmob\jobs\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\jobs\JobsModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_job".
 *
 * @property int $job_id
 * @property string $job_name
 * @property int $timezone_id
 * @property int $campaign_id
 * @property int $concurrent_calls_limit
 * @property int $answer_timeout
 * @property int $ring_timeout
 * @property int $retry_on_no_answer
 * @property int $retry_on_busy
 * @property string $job_status 0 => Stopped 1 => Running
 * @property string $activation_status 0 => Active 1 => Inactive
 * @property int $time_id
 * @property string $job_dial_status
 */
class Job extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_job';
    }

    public static function getJobNameById($jobId)
    {
        /** @var Job $job */
        $job = static::findOne(['job_id' => $jobId]);
        if ($job instanceof Job) {
            return $job->job_name;
        }else{
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timezone_id', 'campaign_id', 'answer_timeout', 'ring_timeout', 'time_id'], 'required', 'on' => ['creat', 'update']],
            [['timezone_id', 'campaign_id', 'answer_timeout', 'ring_timeout', 'time_id'], 'integer', 'on' => ['creat', 'update']],
            [['job_status', 'job_name'], 'safe'],
            [['job_name'], 'required'],
            [['job_name'], 'unique',],
            [['answer_timeout', 'ring_timeout'], 'number', 'min' => 1, 'max' => 120, 'on' => ['creat', 'update']],
            /*[['concurrent_calls_limit'], 'number', 'min'=>1, 'on'=>['creat','update']],*/
            [['job_status', 'job_name', 'activation_status', 'retry_on_no_answer', 'retry_on_busy'], 'safe'],
            [['activation_status'], 'required', 'on' => 'creat'],
            [['job_name'], 'string', 'min' => 3, 'max' => 40],
            [['job_name'], 'match', 'pattern' => '/^[a-zA-Z0-9][a-zA-Z0-9 ]+$/', 'message' => JobsModule::t('jobs', 'job_name_alphanumeric_validation'),
                'on' => ['creat', 'update']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'job_id' => Yii::t('app', 'Job ID'),
            'job_name' => JobsModule::t('jobs', 'job_name'),
            'timezone_id' => JobsModule::t('jobs', 'tz'),
            'campaign_id' => JobsModule::t('jobs', 'campaign_id'),
            //'concurrent_calls_limit'=>JobsModule::t('jobs', 'conc_call_limit'),
            'answer_timeout' => JobsModule::t('jobs', 'ans_timeout'),
            'ring_timeout' => JobsModule::t('jobs', 'ring_timeout'),
            'retry_on_no_answer' => JobsModule::t('jobs', 'retry_on_no_ans'),
            'retry_on_busy' => JobsModule::t('jobs', 'retry_on_busy'),
            'job_status' => Yii::t('app', 'Job Status'),
            'activation_status' => JobsModule::t('jobs', 'activation_status'),
            'time_id' => JobsModule::t('jobs', 'time_id'),
            'job_dial_status' => JobsModule::t('jobs', 'job_dial_status'),
        ];
    }

    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['cmp_id' => 'campaign_id']);

    }
}
