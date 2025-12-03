<?php

namespace app\modules\ecosmob\queue\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

/**
 * QueueMasterSearch represents the model behind the search form of `app\modules\ecosmob\queue\models\QueueMaster`.
 */
class QueueMasterSearch extends QueueMaster
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'qm_id',
                    'qm_max_waiting_calls',
                    'qm_timeout_sec',
                    'qm_wrap_up_time',
                    'qm_periodic_announcement',
                    'qm_failed_service_id',
                    'qm_failed_action',
                    'qm_exit_key',
                    'qm_interrupt_service_id',
                    'qm_interrupt_action',
                ],
                'integer',
            ],
            [['qm_name', 'qm_extension'], 'string', 'max' => 20],
            [
                [
                    'qm_name',
                    'qm_extension',
                    'qm_strategy',
                    'qm_moh',
                    'qm_language',
                    'qm_info_prompt',
                    'qm_is_recording',
                    'qm_exit_caller_if_no_agent_available',
                    'qm_play_position_on_enter',
                    'qm_play_position_periodically',
                    'qm_periodic_announcement_prompt',
                    'qm_display_name_in_caller_id',
                    'qm_is_failed',
                    'qm_is_interrupt',
                ],
                'safe',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidArgumentException
     */
    public function search($params)
    {
        $query = QueueMaster::find();

        $primaryKey = QueueMaster::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'qm_id' => $this->qm_id,
            'qm_max_waiting_calls' => $this->qm_max_waiting_calls,
            'qm_timeout_sec' => $this->qm_timeout_sec,
            'qm_wrap_up_time' => $this->qm_wrap_up_time,
            'qm_periodic_announcement' => $this->qm_periodic_announcement,
            'qm_failed_service_id' => $this->qm_failed_service_id,
            'qm_failed_action' => $this->qm_failed_action,
            'qm_exit_key' => $this->qm_exit_key,
            'qm_interrupt_service_id' => $this->qm_interrupt_service_id,
            'qm_interrupt_action' => $this->qm_interrupt_action,
        ]);

        $query->andFilterWhere(['like', 'qm_name', $this->qm_name])
            ->andFilterWhere(['like', 'qm_extension', $this->qm_extension])
            ->andFilterWhere(['like', 'qm_strategy', $this->qm_strategy])
            ->andFilterWhere(['like', 'qm_moh', $this->qm_moh])
            ->andFilterWhere(['like', 'qm_language', $this->qm_language])
            ->andFilterWhere(['like', 'qm_info_prompt', $this->qm_info_prompt])
            ->andFilterWhere(['like', 'qm_is_recording', $this->qm_is_recording])
            ->andFilterWhere(['like', 'qm_exit_caller_if_no_agent_available', $this->qm_exit_caller_if_no_agent_available])
            ->andFilterWhere(['like', 'qm_play_position_on_enter', $this->qm_play_position_on_enter])
            ->andFilterWhere(['like', 'qm_play_position_periodically', $this->qm_play_position_periodically])
            ->andFilterWhere(['like', 'qm_periodic_announcement_prompt', $this->qm_periodic_announcement_prompt])
            ->andFilterWhere(['like', 'qm_display_name_in_caller_id', $this->qm_display_name_in_caller_id])
            ->andFilterWhere(['like', 'qm_is_failed', $this->qm_is_failed])
            ->andFilterWhere(['like', 'qm_is_interrupt', $this->qm_is_interrupt]);

        return $dataProvider;
    }
}
