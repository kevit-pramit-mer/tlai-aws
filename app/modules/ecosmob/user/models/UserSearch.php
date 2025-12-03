<?php

namespace app\modules\ecosmob\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `app\modules\ecosmob\user\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_id'], 'integer'],
            [['adm_firstname', 'adm_lastname', 'adm_email', 'adm_password', 'adm_contact', 'adm_is_admin', 'adm_status', 'adm_last_login', 'adm_username'], 'safe'],
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
        $query = User::find();

        $primaryKey = User::primaryKey()[0];

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
            'adm_id' => $this->adm_id,
            'adm_last_login' => $this->adm_last_login
        ]);

        $query->andFilterWhere(['like', 'adm_firstname', $this->adm_firstname])
            ->andFilterWhere(['like', 'adm_lastname', $this->adm_lastname])
            ->andFilterWhere(['<>', 'adm_is_admin', 'super_admin'])
            ->andFilterWhere(['<>', 'adm_is_admin', 'supervisor'])
            ->andFilterWhere(['<>', 'adm_is_admin', 'agent'])
            ->andFilterWhere(['<>', 'adm_status', '2'])
            ->andFilterWhere(['like', 'adm_email', $this->adm_email])
            ->andFilterWhere(['like', 'adm_username', $this->adm_username])
            ->andFilterWhere(['like', 'adm_password', $this->adm_password])
            ->andFilterWhere(['like', 'adm_contact', $this->adm_contact])
            ->andFilterWhere(['like', 'adm_is_admin', $this->adm_is_admin])
            ->andFilterWhere(['like', 'adm_status', $this->adm_status]);

        return $dataProvider;
    }

    public function trashedsearch($params)
    {
        $query = User::find();

        $primaryKey = User::primaryKey()[0];

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
            'adm_id' => $this->adm_id,
            'adm_last_login' => $this->adm_last_login,
            'adm_status' => '2'
        ]);

        $query->andFilterWhere(['like', 'adm_firstname', $this->adm_firstname])
            ->andFilterWhere(['like', 'adm_lastname', $this->adm_lastname])
            ->andFilterWhere(['<>', 'adm_is_admin', 'super_admin'])
            ->andFilterWhere(['like', 'adm_email', $this->adm_email])
            ->andFilterWhere(['like', 'adm_username', $this->adm_username])
            ->andFilterWhere(['like', 'adm_password', $this->adm_password])
            ->andFilterWhere(['like', 'adm_contact', $this->adm_contact])
            ->andFilterWhere(['like', 'adm_is_admin', $this->adm_is_admin])
            ->andFilterWhere(['like', 'adm_status', $this->adm_status]);

        return $dataProvider;
    }
}
