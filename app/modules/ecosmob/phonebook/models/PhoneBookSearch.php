<?php

namespace app\modules\ecosmob\phonebook\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PhoneBookSearch represents the model behind the search form of `app\modules\ecosmob\phonebook\models\Phonebook`.
 */
class PhoneBookSearch extends Phonebook
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ph_id', 'ph_extension'], 'integer'],
            [['ph_display_name'], 'string', 'max' => 50],
            [['ph_phone_number'], 'string', 'max' => 15],
            [['ph_first_name', 'ph_last_name', 'ph_display_name', 'ph_phone_number', 'ph_cell_number', 'ph_email_id'], 'safe'],
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
        $query = Phonebook::find();

        $primaryKey = Phonebook::primaryKey()[0];

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
            'ph_id' => $this->ph_id,
        ]);

        if (Yii::$app->session->get('loginAsExtension')) {
            $query->andFilterWhere(['like', 'em_extension', trim(Yii::$app->user->identity->em_extension_number)]);
        }

        $query->andFilterWhere(['like', 'ph_first_name', $this->ph_first_name])
            ->andFilterWhere(['like', 'ph_last_name', $this->ph_last_name])
            ->andFilterWhere(['like', 'ph_display_name', $this->ph_display_name])
            ->andFilterWhere(['like', 'ph_phone_number', $this->ph_phone_number])
            ->andFilterWhere(['like', 'ph_cell_number', $this->ph_cell_number])
            ->andFilterWhere(['like', 'ph_email_id', $this->ph_email_id]);

        return $dataProvider;
    }
}
