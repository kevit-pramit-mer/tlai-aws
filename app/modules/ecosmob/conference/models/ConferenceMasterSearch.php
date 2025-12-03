<?php

namespace app\modules\ecosmob\conference\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ConferenceMasterSearch represents the model behind the search form about `app\modules\ecosmob\conference\models\ConferenceMaster`.
 */
class ConferenceMasterSearch extends ConferenceMaster
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cm_id', 'cm_max_participant'], 'integer'],
            [['cm_name', 'cm_extension'], 'string', 'max' => 32],
            [
                [
                    'cm_name',
                    'cm_status',
                    'cm_part_code',
                    'cm_mod_code',
                    'cm_quick_start',
                    'cm_entry_tone',
                    'cm_exit_tone',
                    'cm_moh',
                    'cm_extension',
                    'cm_language',
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
        $query = ConferenceMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['cm_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        //        $query->andWhere(['=', 'user_id', Yii::$app->user->identity->user_id]);
        $query->andFilterWhere([
            'cm_id' => $this->cm_id,
            'cm_max_participant' => $this->cm_max_participant,
        ]);

        $query->andFilterWhere(['like', 'cm_name', $this->cm_name])
            ->andFilterWhere(['like', 'cm_extension', $this->cm_extension])
            ->andFilterWhere(['like', 'cm_status', $this->cm_status])
            ->andFilterWhere([
                'like',
                'cm_part_code',
                $this->cm_part_code,
            ])
            ->andFilterWhere(['like', 'cm_mod_code', $this->cm_mod_code])
            ->andFilterWhere([
                'like',
                'cm_quick_start',
                $this->cm_quick_start,
            ])
            ->andFilterWhere(['like', 'cm_entry_tone', $this->cm_entry_tone])
            ->andFilterWhere([
                'like',
                'cm_exit_tone',
                $this->cm_exit_tone,
            ]);

        return $dataProvider;
    }

    public function searchExtension($params)
    {
        $query = ConferenceMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['cm_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        //        $query->andWhere(['=', 'user_id', Yii::$app->user->identity->user_id]);
        $query->andFilterWhere([
            'cm_id' => $this->cm_id,
            //            'sp_id' => $this->sp_id,
            //            'user_id' => $this->user_id,
            'cm_max_participant' => $this->cm_max_participant,
        ]);

        $query->andFilterWhere(['like', 'cm_language', $this->cm_language])->andFilterWhere(['like', 'cm_name', $this->cm_name])
            ->andFilterWhere(['like', 'cm_status', $this->cm_status])->andFilterWhere([
                'like',
                'cm_part_code',
                $this->cm_part_code,
            ])->andFilterWhere(['like', 'cm_mod_code', $this->cm_mod_code])->andFilterWhere([
                'like',
                'cm_quick_start',
                $this->cm_quick_start,
            ])->andFilterWhere(['like', 'cm_entry_tone', $this->cm_entry_tone])->andFilterWhere([
                'like',
                'cm_exit_tone',
                $this->cm_exit_tone,
            ]);

        return $dataProvider;
    }
}
