<?php

namespace app\modules\ecosmob\supervisor\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminMasterSearch represents the model behind the search form of `app\modules\ecosmob\supervisor\models\AdminMaster`.
 */
class AdminMasterSearch extends AdminMaster
{
    public $uname;
    public $from;
    public $to;
    public $user_type;
    public $campaign;

    public function rules()
    {
        return [
            [['adm_id', 'adm_timezone_id'], 'integer'],
            [['adm_firstname', 'adm_lastname', 'uname', 'adm_email', 'adm_password', 'adm_contact', 'adm_is_admin', 'adm_status', 'adm_last_login', 'from', 'to', 'user_type', 'in_time', 'campaign', 'adm_mapped_extension'], 'safe'],
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
            ->andFilterWhere(['like', 'adm_status', $this->adm_status])
            ->andFilterWhere(['like', 'adm_is_admin', 'supervisor']);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function agentsearch($params)
    {
        $query = UsersActivityLog::find()
            ->select(['adp.user_id', 'ccc.cmp_name as campaign_name', 'am.adm_firstname', 'am.adm_lastname', 'am.adm_is_admin',
                'DATE_FORMAT(adp.created_at, "%d/%m/%Y") as date',
                'CONCAT(
                      FLOOR((TIMESTAMPDIFF(SECOND, adp.login_time, adp.logout_time) % 86400)/3600)," : ", 
                      FLOOR((TIMESTAMPDIFF(SECOND, adp.login_time, adp.logout_time) % 3600)/60)," : ",
                      (TIMESTAMPDIFF(SECOND, adp.login_time, adp.logout_time) % 60) 
                    )as login_time',

                'CONCAT(
                      FLOOR((TIMESTAMPDIFF(SECOND, brm.in_time, brm.out_time) % 86400)/3600)," : ", 
                      FLOOR((TIMESTAMPDIFF(SECOND, brm.in_time, brm.out_time) % 3600)/60)," : ",
                      (TIMESTAMPDIFF(SECOND, brm.in_time, brm.out_time) % 60) 
                    ) as break_time',
            ])
            ->from('users_activity_log adp')
            ->leftJoin('admin_master am', 'am.adm_id=adp.user_id')
            ->leftJoin('ct_call_campaign ccc', 'ccc.cmp_id=adp.campaign_name')
            ->leftJoin('break_reason_mapping brm', 'brm.user_id=adp.user_id AND DATE_FORMAT(adp.created_at, "%d/%m/%Y") = DATE_FORMAT(brm.in_time, "%d/%m/%Y")')
            ->groupby(['adp.user_id', 'DATE_FORMAT(adp.created_at, "%d/%m/%Y")']);

        $primaryKey = UsersActivityLog::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if (!empty($this->from) && !empty($this->to)) {
            $from = $this->from . ' 00:00:00';
            $to = $this->to . ' 23:59:59';
            $query->andFilterWhere(['BETWEEN', 'adp.created_at', $from, $to]);
        }

        $query->andFilterWhere(['like', 'am.adm_is_admin', $this->user_type])
            ->andFilterWhere(['like', 'ccc.cmp_name', $this->campaign]);


        return $dataProvider;
    }
}
