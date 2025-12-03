<?php

namespace app\modules\ecosmob\ringgroup\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

/**
 * RingGroupSearch represents the model behind the search form of `app\modules\ecosmob\ringgroup\models\RingGroup`.
 */
class RingGroupSearch extends RingGroup
{

    /*
     * {@inheritdoc}
     */

    public $rm_type;
    public $rm_number, $number;


    public function rules()
    {
        return [
            [
                ['rg_id', 'rg_timeout_sec', 'rg_failed_service_id', 'rg_failed_action', 'rg_status', 'rg_callerid_name', 'rg_call_confirm'],
                'integer',
            ],
            [
                [
                    'rg_name',
                    'rg_language',
                    'number',
                    'rg_type',
                    'rg_info_prompt',
                    'rg_is_recording',
                    'rg_is_failed',
                    'rg_call_feature',
                    'updated_date',
                    'created_date',
                ],
                'safe',
            ],
            [['rm_type', 'rm_number'], 'safe'],
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
        $query = RingGroup::find();


        $primaryKey = RingGroup::primaryKey()[0];

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
            'rg_id' => $this->rg_id,
            'rg_language' => $this->rg_language,
            'rg_timeout_sec' => $this->rg_timeout_sec,
            'rg_failed_service_id' => $this->rg_failed_service_id,
            'rg_failed_action' => $this->rg_failed_action,
            'rg_callerid_name' => $this->rg_callerid_name,
            'rg_call_confirm' => $this->rg_call_confirm,
        ]);

        $query->andFilterWhere(['like', 'rg_name', $this->rg_name])
            ->andFilterWhere(['like', 'rg_extension', $this->number])
            ->andFilterWhere(['like', 'rg_type', $this->rg_type])
            ->andFilterWhere(['like', 'rg_info_prompt', $this->rg_info_prompt])
            ->andFilterWhere(['like', 'rg_is_recording', $this->rg_is_recording])
            ->andFilterWhere(['like', 'rg_is_failed', $this->rg_is_failed])
            ->andFilterWhere(['like', 'rg_call_feature', $this->rg_call_feature])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'rg_status', $this->rg_status]);

        return $dataProvider;
    }
}
