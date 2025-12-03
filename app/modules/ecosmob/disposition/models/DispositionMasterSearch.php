<?php

namespace app\modules\ecosmob\disposition\models;

use app\modules\ecosmob\disposition\DispositionModule;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DispositionMasterSearch represents the model behind the search form of `app\modules\ecosmob\disposition\models\DispositionMaster`.
 */
class DispositionMasterSearch extends DispositionMaster
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ds_id'], 'integer'],
            ['ds_name', 'match', 'pattern' => '/^[A-Za-z ]+$/', 'message' => DispositionModule::t('disposition', 'invalid_charcter_in_dispostion_name')],
            [['ds_name', 'ds_description', 'ds_type'], 'safe'],
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
        $query = DispositionMaster::find()->select([
            'ct_disposition_master.*',
//            'dt.ds_type'
        ])->from('ct_disposition_master')
        ->where(['NOT IN', 'is_default', [1, 2]]);
//            ->leftjoin('ct_disposition_type dt', 'dt.ds_id = ct_disposition_master.ds_id')
//            ->groupBy('dt.ds_id');

        $primaryKey = DispositionMaster::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ds_id' => SORT_DESC],
                'attributes' => [
                    'ds_id' => [
                        'asc' => ['ds_id' => SORT_ASC],
                        'desc' => ['ds_id' => SORT_DESC],
                    ],
                    'ds_name' => [
                        'asc' => ['ds_name' => SORT_ASC],
                        'desc' => ['ds_name' => SORT_DESC],
                    ],
                    'defaultOrder' => [$primaryKey => SORT_DESC],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ds_id' => $this->ds_id,
        ]);

        $query->andFilterWhere(['like', 'ds_name', $this->ds_name])
            ->andFilterWhere(['like', 'ds_description', $this->ds_description]);

        return $dataProvider;
    }
}
