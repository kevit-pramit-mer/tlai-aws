<?php

namespace app\modules\ecosmob\autoattendant\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

/**
 * AutoAttendantMasterSearch represents the model behind the search form about `app\modules\ecosmob\autoattendant\models\AutoAttendantMaster`.
 */
class AutoAttendantMasterSearch extends AutoAttendantMaster
{
    public $aam_ext;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aam_id', 'aam_mapped_id', 'aam_level'], 'integer'],
            [
                [
                    'aam_name',
                    'aam_ext',
                    'aam_greet_long',
                    'aam_greet_short',
                    'aam_invalid_sound',
                    'aam_failure_prompt',
                    'aam_timeout_prompt',
                    'aam_exit_sound',
                    'aam_timeout',
                    'aam_inter_digit_timeout',
                    'aam_language',
                    'aam_status',
                    'aam_direct_dial',
                    'aam_transfer_on_failure',
                    'aam_digit_len',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
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
        $query = AutoAttendantMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['aam_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'aam_id' => $this->aam_id,
            'aam_mapped_id' => $this->aam_mapped_id,
            'aam_level' => $this->aam_level,
        ]);

        $query->where(['<>', 'aam_status', 'X']);

        $query->andFilterWhere(['aam_level' => 0]);
        // $query->andWhere(['em_id' => 0]);

        $query->andFilterWhere(['like', 'aam_name', $this->aam_name])
            ->andFilterWhere(['like', 'aam_greet_long', $this->aam_greet_long])
            ->andFilterWhere(['like', 'aam_greet_short', $this->aam_greet_short])
            ->andFilterWhere(['like', 'aam_invalid_sound', $this->aam_invalid_sound])
            ->andFilterWhere(['like', 'aam_exit_sound', $this->aam_exit_sound])
            ->andFilterWhere(['like', 'aam_timeout', $this->aam_timeout])
            ->andFilterWhere(['like', 'aam_inter_digit_timeout', $this->aam_inter_digit_timeout])
            ->andFilterWhere(['like', 'aam_language', $this->aam_language])
            ->andFilterWhere(['like', 'aam_status', $this->aam_status])
            ->andFilterWhere(['like', 'aam_extension', $this->aam_ext]);

        return $dataProvider;
    }
}
