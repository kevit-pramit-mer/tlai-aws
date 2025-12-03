<?php

namespace app\modules\ecosmob\carriertrunk\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TrunkMasterSearch represents the model behind the search form about `app\modules\ecosmob\carriertrunk\models\TrunkMaster`.
 */
class TrunkMasterSearch extends TrunkMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trunk_id'], 'integer'],
            [
                ['trunk_name'],
                'string',
                'max' => 25,
            ],
            [['trunk_ip'], 'string', 'max' => 45],
            [
                ['trunk_ip'],
                'match', 'pattern' => "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i",
                'when' => function ($model) {
                    //district required if country is set
                    return $model->trunk_ip != null;
                },
                'whenClient' => "function (attribute, value) {
                    return ($('#trunkmastersearch-trunk_ip').val() != '');
                }",
                /*"message" => CarriertrunkModule::t(
                    'carriertrunk',
                    'Invalid Domain'),*/ 'enableClientValidation' => true
            ],
            [
                [
                    'trunk_name',
                    'trunk_ip',
                    'trunk_proxy_ip',
                    'trunk_register',
                    'trunk_username',
                    'trunk_password',
                    'trunk_add_prefix',
                    'trunk_status',
                    'trunk_ip_type',
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
     */
    public function search($params)
    {
        $query = TrunkMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['trunk_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andwhere(['from_service' => '0']);
        $query->andwhere(['<>', 'trunk_status', 'X']);

        // grid filtering conditions
        $query->andFilterWhere([
            'trunk_id' => $this->trunk_id,
        ]);

        $query->andFilterWhere(['like', 'trunk_name', $this->trunk_name])->andFilterWhere([
            'like',
            'trunk_ip',
            $this->trunk_ip,
        ])->andFilterWhere(['like', 'trunk_proxy_ip', $this->trunk_proxy_ip])->andFilterWhere([
            'like',
            'trunk_register',
            $this->trunk_register,
        ])->andFilterWhere(['like', 'trunk_username', $this->trunk_username])->andFilterWhere([
            'like',
            'trunk_password',
            $this->trunk_password,
        ])->andFilterWhere(['like', 'trunk_add_prefix', $this->trunk_add_prefix])->andFilterWhere([
            'like',
            'trunk_status',
            $this->trunk_status,
        ])->andFilterWhere(['like', 'trunk_ip_type', $this->trunk_ip_type]);

        return $dataProvider;
    }
}
