<?php

namespace app\modules\ecosmob\enterprisePhonebook\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook;

/**
 * EnterprisePhonebookSearch represents the model behind the search form of `app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook`.
 */
class EnterprisePhonebookSearch extends EnterprisePhonebook
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'trago_ed_id'], 'integer'],
            [['en_first_name', 'en_last_name', 'en_extension', 'en_mobile', 'en_phone', 'en_email_id', 'em_status'], 'safe'],
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
        $query = EnterprisePhonebook::find();

        $primaryKey = EnterprisePhonebook::primaryKey()[0];

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
            'id' => $this->id,
            'trago_ed_id' => $this->trago_ed_id,
        ]);

        $query->andFilterWhere(['=', 'en_extension', $this->en_extension])
            ->andFilterWhere(['like', 'en_first_name', $this->en_first_name])
            ->andFilterWhere(['like', 'en_last_name', $this->en_last_name])
            ->andFilterWhere(['like', 'en_mobile', $this->en_mobile])
            ->andFilterWhere(['like', 'en_phone', $this->en_phone])
            ->andFilterWhere(['like', 'en_email_id', $this->en_email_id]);

        return $dataProvider;
    }
}
