<?php

namespace app\modules\ecosmob\queue\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\queue\QueueModule;
use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_queue_master".
 *
 * @property int $qm_id       Auto increment ID
 * @property string $qm_name     Queue Name
 * @property string $qm_extension
 * @property string $qm_strategy QUEUE strategy
 * @property string $qm_moh      Moh to be played to caller while on queue
 * @property string $qm_language
 * @property string $qm_info_prompt
 * @property int $qm_max_waiting_calls
 * @property int $qm_timeout_sec
 * @property int $qm_wrap_up_time
 * @property string $qm_is_recording
 * @property string $qm_exit_caller_if_no_agent_available
 * @property string $qm_play_position_on_enter
 * @property string $qm_play_position_periodically
 * @property int $qm_periodic_announcement
 * @property string $qm_periodic_announcement_prompt
 * @property string $qm_display_name_in_caller_id
 * @property string $qm_is_failed
 * @property int $qm_failed_service_id
 * @property int $qm_failed_action
 * @property string $qm_is_interrupt
 * @property int $qm_exit_key
 * @property int $qm_interrupt_service_id
 * @property int $qm_interrupt_action
 * @property int $qm_auto_answer
 * @property int $qm_callback
 */
class QueueMaster extends ActiveRecord
{

    public $agents;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_queue_master';
    }

    /**
     * @return array
     */
    public static function getQueueList()
    {
        if ($dataModel = QueueMaster::find()->select(['qm_extension', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->all()) {
            return ArrayHelper::map($dataModel, 'qm_extension', 'qm_name');
        } else {
            return [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'qm_name',
                    'qm_extension',
                    'qm_moh',
                    'qm_language',
                    'qm_info_prompt',
                    'qm_max_waiting_calls',
                    'qm_timeout_sec',
                    'qm_wrap_up_time',
                    'qm_is_recording',
                    'qm_exit_caller_if_no_agent_available',
                    'qm_play_position_on_enter',
                    'qm_play_position_periodically',
                    'qm_periodic_announcement',
                    'qm_display_name_in_caller_id',
                    'qm_is_failed',
                    'qm_is_interrupt',
                    'qm_auto_answer',
                    'qm_callback',
                ],
                'required',
            ],
            [
                [
                    'qm_strategy',
                    'qm_is_recording',
                    'qm_exit_caller_if_no_agent_available',
                    'qm_play_position_on_enter',
                    'qm_play_position_periodically',
                    'qm_display_name_in_caller_id',
                    'qm_auto_answer',
                    'qm_callback',
                    'qm_is_failed',
                    'qm_is_interrupt',
                ],
                'string',
            ],
            [
                [
                    'qm_failed_service_id',
                    'qm_failed_action',
                    'qm_interrupt_service_id',
                    'qm_interrupt_action',
                ],
                'integer',
            ],
            [['agents', 'qm_periodic_announcement_prompt'], 'safe'],
            [['qm_exit_key'], 'string', 'max' => 1],
            [['qm_periodic_announcement'], 'integer', 'min' => 10, 'max' => 999],
            [['qm_periodic_announcement'], 'string', 'min' => 1, 'max' => 3],
            [['qm_wrap_up_time'], 'integer', 'min' => 20, 'max' => 9999],
            [['qm_timeout_sec', 'qm_max_waiting_calls'], 'integer', 'min' => 0, 'max' => 9999],
            [['qm_name'], 'string', 'max' => 20],
            [['qm_name'], 'match', 'pattern' => '/^[0-9a-zA-Z]*$/', 'message' => QueueModule::t('queue', 'queue_name_validation')],
            [['qm_name'], 'checkQueueUnique'],
            [['qm_extension'], 'integer'],
            [['qm_extension'], 'checkUnique'],
            [['qm_extension'], 'string', 'min' => 3, 'max' => 20],
            [['qm_language'], 'string', 'max' => 30],
            //[['qm_periodic_announcement_prompt'], 'string', 'max' => 40],

            [
                ['qm_failed_action', 'qm_failed_service_id'],
                'required',
                'when' => function ($model) {
                    return $model->qm_is_failed == 1;
                },
                'whenClient' => "function (attribute, value) {
                            return $('#is_failed:checked').val() == '1';
          }",
            ],
            [
                ['qm_interrupt_action', 'qm_interrupt_service_id', 'qm_exit_key'],
                'required',
                'when' => function ($model) {
                    return $model->qm_is_interrupt == 1;
                },
                'whenClient' => "function (attribute, value) {
                            return $('#is_interrupt:checked').val() == '1';
          }",
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'qm_id' => QueueModule::t('queue', 'id'),
            'qm_name' => QueueModule::t('queue', 'name'),
            'qm_extension' => QueueModule::t('queue', 'extension'),
            'qm_strategy' => QueueModule::t('queue', 'strategy'),
            'qm_moh' => QueueModule::t('queue', 'moh'),
            'qm_language' => QueueModule::t('queue', 'language'),
            'qm_info_prompt' => QueueModule::t('queue', 'info_prompt'),
            'qm_max_waiting_calls' => QueueModule::t('queue', 'max_waiting_calls'),
            'qm_timeout_sec' => QueueModule::t('queue', 'timeout'),
            'qm_wrap_up_time' => QueueModule::t('queue', 'wrap_up_time'),
            'qm_is_recording' => QueueModule::t('queue', 'is_recording'),
            'qm_exit_caller_if_no_agent_available' => QueueModule::t('queue', 'exit_caller'),
            'qm_play_position_on_enter' => QueueModule::t('queue', 'play_position'),
            'qm_play_position_periodically' => QueueModule::t('queue', 'play_position_periodically'),
            'qm_periodic_announcement' => QueueModule::t('queue', 'periodic_announcement'),
            'qm_periodic_announcement_prompt' => QueueModule::t('queue', 'periodic_announcement_prompt'),
            'qm_display_name_in_caller_id' => QueueModule::t('queue', 'display_name'),
            'qm_auto_answer' => QueueModule::t('queue', 'qm_auto_answer'),
            'qm_callback' => QueueModule::t('queue', 'qm_callback'),
            'qm_is_failed' => QueueModule::t('queue', 'is_failed'),
            'qm_failed_service_id' => QueueModule::t('queue', 'failed_service_id'),
            'qm_failed_action' => QueueModule::t('queue', 'failed_action'),
            'qm_is_interrupt' => QueueModule::t('queue', 'is_interrupt'),
            'qm_exit_key' => QueueModule::t('queue', 'exit_key'),
            'qm_interrupt_service_id' => QueueModule::t('queue', 'interrupt_service_id'),
            'qm_interrupt_action' => QueueModule::t('queue', 'interrupt_action'),
        ];
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->qm_extension, $this->getOldAttribute('qm_extension'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }

    public function canDelete($id)
    {
        /** @var Campaign $campaignCount */
        $campaignCount = Campaign::find()->where(['cmp_queue_id' => $id])->count();

        if ($campaignCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public static function getQueueName($queueName)
    {
        $qm_name = explode('_',$queueName);
        if(is_array($qm_name)){
            return $qm_name[0];
        }
        return $qm_name;
    }

    /**
     * @param $attribute
     */
    public function checkQueueUnique($attribute){
        if(!empty($this->qm_name)) {
            $queue = QueueMaster::find()->andWhere(['=', new \yii\db\Expression("LOWER(SUBSTRING_INDEX(qm_name, '_', 1))"), strtolower($this->qm_name)]);
            if(!empty($this->qm_id)){
                $queue = $queue->andWhere(['!=', 'qm_id', $this->qm_id]);
            }
            $queue = $queue->count();
            if($queue > 0){
                $this->addError($attribute, QueueModule::t('queue', 'qmNameUniqueError', ['name' => $this->qm_name]));
            }
        }
    }
}
