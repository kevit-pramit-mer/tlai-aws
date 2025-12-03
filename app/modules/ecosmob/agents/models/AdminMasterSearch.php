<?php

namespace app\modules\ecosmob\agents\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminMasterSearch represents the model behind the search form of `app\modules\ecosmob\agents\models\AdminMaster`.
 */
class AdminMasterSearch extends AdminMaster
{
    public $uname;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_id', 'adm_timezone_id'], 'integer'],
            [['adm_firstname', 'adm_lastname', 'uname', 'adm_email', 'adm_password', 'adm_contact', 'adm_is_admin', 'adm_status', 'adm_last_login', 'adm_mapped_extension'], 'safe'],
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
        $query = AdminMaster::find();

        $primaryKey = AdminMaster::primaryKey()[0];

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
            'adm_timezone_id' => $this->adm_timezone_id,
            'adm_last_login' => $this->adm_last_login,
            'adm_mapped_extension' => $this->adm_mapped_extension
        ]);

        $query->andFilterWhere(['like', 'adm_firstname', $this->adm_firstname])
            ->andFilterWhere(['like', 'adm_lastname', $this->adm_lastname])
            ->andFilterWhere(['like', 'adm_username', $this->uname])
            ->andFilterWhere(['like', 'adm_email', $this->adm_email])
            ->andFilterWhere(['like', 'adm_password', $this->adm_password])
            ->andFilterWhere(['like', 'adm_is_admin', $this->adm_is_admin])
            ->andFilterWhere(['like', 'adm_status', $this->adm_status])
            ->andFilterWhere(['like', 'adm_is_admin', 'agent']);

        return $dataProvider;
    }
}
