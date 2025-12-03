<?php

namespace app\modules\ecosmob\accessrestriction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AccessRestrictionSearch represents the model behind the search form of `app\modules\ecosmob\accessrestriction\models\AccessRestriction`.
 */
class AccessRestrictionSearch extends AccessRestriction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ar_id', 'ar_maskbit'], 'integer'],
            [['ar_ipaddress', 'ar_description', 'ar_status'], 'safe'],
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
        $query = AccessRestriction::find();

        $primaryKey = AccessRestriction::primaryKey()[0];

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
            'ar_id' => $this->ar_id,
            'ar_status' => $this->ar_status,
            'ar_maskbit' => $this->ar_maskbit,
        ]);

        $query->andFilterWhere(['like', 'ar_ipaddress', $this->ar_ipaddress])
            ->andFilterWhere(['like', 'ar_description', $this->ar_description])
            ->andFilterWhere(['like', 'ar_status', $this->ar_status]);

        return $dataProvider;
    }
}
