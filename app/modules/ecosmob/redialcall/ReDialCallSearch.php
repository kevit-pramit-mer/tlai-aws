<?php

namespace app\modules\ecosmob\redialcall;

use app\modules\ecosmob\redialcall\models\ReDialCall;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReDialCallSearch represents the model behind the search form of `app\modules\ecosmob\redialcall\models\ReDialCall`.
 */
class ReDialCallSearch extends ReDialCall
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ld_id'], 'integer'],
            [['ld_group_name'], 'safe'],
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
        $query = ReDialCall::find();

        $primaryKey = ReDialCall::primaryKey()[0];

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
            'ld_id' => $this->ld_id,
        ]);

        $query->andFilterWhere(['like', 'ld_group_name', $this->ld_group_name]);

        return $dataProvider;
    }
}
