<?php

namespace app\modules\ecosmob\manageagent\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ManageAgentSearch represents the model behind the search form of `app\modules\ecosmob\manageagent\models\ManageAgent`.
 */
class ManageAgentSearch extends ManageAgent
{
    public $campaign;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_id', 'adm_timezone_id'], 'integer'],
            [['campaign', 'adm_firstname', 'adm_lastname', 'adm_username', 'adm_email', 'adm_password', 'adm_password_hash', 'adm_contact', 'adm_is_admin', 'adm_status', 'adm_last_login'], 'safe'],
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
        $campaignList = [];
        $supervisorCamp = CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }

        $query = ManageAgent::find()->select([
            //'admin_master.*',
            "cc.cmp_name AS campaign",
            "admin_master.adm_firstname",
            "admin_master.adm_lastname",
            "admin_master.adm_username",
            "admin_master.adm_is_admin",
            "admin_master.adm_status",
            'recent_login' => UsersActivityLog::find()
                ->select(['MAX(login_time)'])
                ->andWhere('user_id = admin_master.adm_id')
                ->andWhere('FIND_IN_SET(cc.cmp_id, campaign_name)')
        ])->leftJoin('campaign_mapping_agents ma', 'ma.agent_id = admin_master.adm_id')
            ->leftJoin('ct_call_campaign cc', 'cc.cmp_id = ma.campaign_id')
            ->andWhere(['adm_is_admin' => 'agent'])
            ->andWhere(['cc.cmp_status' => 'Active'])
            ->andWhere(['cc.cmp_id' => $campaignList])
            ->andWhere("admin_master.adm_id = ma.agent_id ")
            ->groupBy("cc.cmp_id, admin_master.adm_id");

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'adm_firstname' => [
                        'asc' => ['adm_firstname' => SORT_ASC],
                        'desc' => ['adm_firstname' => SORT_DESC],
                    ],
                    'adm_lastname' => [
                        'asc' => ['adm_lastname' => SORT_ASC],
                        'desc' => ['adm_lastname' => SORT_DESC],
                    ],
                    'adm_username' => [
                        'asc' => ['adm_username' => SORT_ASC],
                        'desc' => ['adm_username' => SORT_DESC],
                    ],
                    'adm_status' => [
                        'asc' => ['adm_status' => SORT_ASC],
                        'desc' => ['adm_status' => SORT_DESC],
                    ],
                    'campaign' => [
                        'asc' => ['campaign' => SORT_ASC],
                        'desc' => ['campaign' => SORT_DESC],
                    ],
                    'recent_login' => [
                        'asc' => ['recent_login' => SORT_ASC],
                        'desc' => ['recent_login' => SORT_DESC],
                    ],
                    'defaultOrder' => ['recent_login' => SORT_DESC]
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /*if (isset($params['ManageAgentSearch']['campaign']) && !empty($params['ManageAgentSearch']['campaign'])) {
            $supervisor=CampaignMappingUser::find()->select(['supervisor_id'])->where(['campaign_id'=>$params['ManageAgentSearch']['campaign']])->asArray()->all();
            $agents=CampaignMappingAgents::find()->select(['agent_id'])->where(['campaign_id'=>$params['ManageAgentSearch']['campaign']])->asArray()->all();

            $superList=array();
            $agentsList=array();
            if (!empty($supervisor)) {
                foreach ($supervisor as $single) {
                    $superList[]=trim($single['supervisor_id']);
                }
            }
            if (!empty($agents)) {
                foreach ($agents as $singleAgents) {
                    $agentsList[]=trim($singleAgents['agent_id']);
                }
            }
        }
        if (isset($superList) && !empty($superList)) {
            $query->andWhere(['adm_id'=>$superList])->orWhere(['adm_id'=>$agentsList]);
        }*/

        /*if (isset($params['ManageAgentSearch']['adm_is_admin']) == 'No') {
            if (isset($superList) && !empty($superList)) {
                $query->andWhere(['adm_id' => $superList]);
            }
        }else if (isset($params['ManageAgentSearch']['adm_is_admin']) == 'Yes'){
            if (isset($superList) && !empty($superList)) {
                $query->andWhere(['adm_id' => $superList]);
            }
        }*/
        if ($this->campaign) {
            $query->andFilterWhere(['=', 'cc.cmp_id', $this->campaign]);
        }
        $query->andFilterWhere(['like', 'adm_firstname', $this->adm_firstname])
            ->andFilterWhere(['like', 'adm_lastname', $this->adm_lastname])
            ->andFilterWhere(['like', 'adm_username', $this->adm_username])
            ->andFilterWhere(['like', 'adm_email', $this->adm_email])
            ->andFilterWhere(['like', 'adm_status', $this->adm_status]);

        return $dataProvider;
    }
}
