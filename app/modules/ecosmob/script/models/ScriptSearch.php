<?php

namespace app\modules\ecosmob\script\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ScriptSearch represents the model behind the search form of `app\modules\ecosmob\script\models\Script`.
 */
class ScriptSearch extends Script
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scr_id'], 'integer'],
            [['scr_name', 'scr_description', 'scr_status'], 'safe'],
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
     */
    public function search($params)
    {
        $query = Script::find();

        $primaryKey = Script::primaryKey()[0];

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
            'scr_id' => $this->scr_id,
        ]);

        $query->andFilterWhere(['like', 'scr_name', $this->scr_name])
            ->andFilterWhere(['like', 'scr_description', $this->scr_description])
            ->andFilterWhere(['like', 'scr_status', $this->scr_status]);

        return $dataProvider;
    }
}
