<?php

namespace app\modules\ecosmob\audiomanagement\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AudioManagementSearch represents the model behind the search form of `app\modules\ecosmob\audiomanagement\models\AudioManagement`.
 */
class AudioManagementSearch extends AudioManagement
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['af_id'], 'integer'],
            [['af_name', 'af_type', 'af_language', 'af_description', 'af_file', 'af_extension'], 'safe'],
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
        $query = AudioManagement::find();

        $primaryKey = AudioManagement::primaryKey()[0];

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
            'af_id' => $this->af_id,
        ]);

        $query->andFilterWhere(['like', 'af_name', $this->af_name])
            ->andFilterWhere(['like', 'af_type', $this->af_type])
            ->andFilterWhere(['like', 'af_language', $this->af_language])
            ->andFilterWhere(['like', 'af_extension', $this->af_extension])
            ->andFilterWhere(['like', 'af_description', $this->af_description])
            ->andFilterWhere(['like', 'af_file', $this->af_file]);

        return $dataProvider;
    }
}
