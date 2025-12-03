<?php

namespace app\modules\ecosmob\holiday\models;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HolidaySearch represents the model behind the search form of `app\modules\ecosmob\holiday\models\Holiday`.
 */
class HolidaySearch extends Holiday
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hd_id'], 'integer'],
            [['hd_holiday', 'hd_date', 'hd_end_date', 'created_date', 'updated_date'], 'safe'],
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
        $query = Holiday::find();

        $primaryKey = Holiday::primaryKey()[0];

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
            'hd_id' => $this->hd_id,
        ]);

        $query->andFilterWhere(['like', 'hd_holiday', $this->hd_holiday]);

        if ($this->hd_date && $this->hd_end_date) {
            $from = CommonHelper::DtTots($this->hd_date . ' 00:00:01');
            $to = CommonHelper::DtTots($this->hd_end_date . ' 23:59:59');
            $query->andFilterWhere(['>=', 'hd_date', trim($from)]);
            $query->andFilterWhere(['<=', 'hd_end_date', trim($to)]);
        } else if ($this->hd_date) {
            $from = CommonHelper::DtTots($this->hd_date . ' 00:00:01');
            $query->andFilterWhere(['>=', 'hd_date', trim($from)]);
        } else if ($this->hd_end_date) {
            $to = CommonHelper::DtTots($this->hd_end_date . ' 23:59:59');
            $query->andFilterWhere(['<=', 'hd_end_date', trim($to)]);
        }

        return $dataProvider;
    }
}
