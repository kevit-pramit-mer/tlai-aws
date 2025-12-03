<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\ipprovisioning\models\DeviceTemplates;

/**
 * DeviceTemplatesSearch represents the model behind the search form of `app\modules\ecosmob\ipprovisioning\models\DeviceTemplates`.
 */
class DeviceTemplatesSearch extends DeviceTemplates
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_templates_id', 'template_name', 'supported_models_id', 'voipservice_key', 'createdAt', 'updatedAt'], 'safe'],
            [['brand_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = DeviceTemplates::find();

        $primaryKey = DeviceTemplates::primaryKey()[0];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'brand_id' => $this->brand_id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'device_templates_id', $this->device_templates_id])
            ->andFilterWhere(['like', 'template_name', $this->template_name])
            ->andFilterWhere(['like', 'supported_models_id', $this->supported_models_id])
            ->andFilterWhere(['like', 'voipservice_key', $this->voipservice_key]);

        return $dataProvider;
    }
}
