<?php

namespace app\modules\ecosmob\customerdetails\models;

use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CampaignMappingUserSearch represents the model behind the search form of `app\modules\ecosmob\customerdetails\models\CampaignMappingUser`.
 */
class CampaignMappingUserSearch extends CampaignMappingUser
{
    public $lg_first_name;
    public $lg_last_name;
    public $lg_contact_number;
    public $campaign;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'campaign_id'], 'integer'],
            [['supervisor_id', 'lg_first_name', 'lg_last_name', 'lg_contact_number', 'campaign_id', 'campaign'], 'safe'],
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
        $supervisorCamp = \app\modules\ecosmob\campaign\models\CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }

        $query = LeadGroupMember::find()
            ->select([
                'ct_lead_group_member.*',
                'ccc.cmp_name as campaign_id',
            ])
            ->from('ct_lead_group_member')
            ->leftjoin('ct_call_campaign ccc', 'ccc.cmp_lead_group = ct_lead_group_member.ld_id')
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->groupBy(['ct_lead_group_member.lg_id']);

        /* $leadGropIds=array();

        if (isset($params['CampaignMappingUserSearch']['campaign_id']) && !empty($params['CampaignMappingUserSearch']['campaign_id'])) {
            $data=Campaign::find()->select('cmp_lead_group')->where(['cmp_id'=>$params['CampaignMappingUserSearch']['campaign_id']])->asArray()->all();


            if (isset($data) && !empty($data)) {
                foreach ($data as $single) {
                    $leadGropIds[]=$single['cmp_lead_group'];
                }
            }
        }

        $campaignIds=CampaignMappingUser::find()->select('campaign_id')->where(['supervisor_id'=>Yii::$app->user->identity->adm_username])->asArray()->all();

        $campLeadGropIds=array();
        if (isset($campaignIds) && !empty($campaignIds)) {
            foreach ($campaignIds as $singleLeads) {
                $campLeadGropIds[]=$singleLeads['campaign_id'];
            }

        }
        $leadData=Campaign::find()->select(['cmp_lead_group'])->where(['cmp_id'=>$campLeadGropIds])->asArray()->all();

        $campLeadGropIdsData=array();
        if (isset($leadData) && !empty($leadData)) {
            foreach ($leadData as $singleLeadsData) {
                $campLeadGropIdsData[]=$singleLeadsData['cmp_lead_group'];
            }
        }*/


        $primaryKey = LeadGroupMember::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' =>
                    [
                        'lg_first_name' => [
                            'asc' => ['lg_first_name' => SORT_ASC],
                            'desc' => ['lg_first_name' => SORT_DESC],
                        ],
                        'lg_last_name' => [
                            'asc' => ['lg_last_name' => SORT_ASC],
                            'desc' => ['lg_last_name' => SORT_DESC],
                        ],
                        'lg_contact_number' => [
                            'asc' => ['lg_contact_number' => SORT_ASC],
                            'desc' => ['lg_contact_number' => SORT_DESC],
                        ],
                        'lg_email_id' => [
                            'asc' => ['lg_email_id' => SORT_ASC],
                            'desc' => ['lg_email_id' => SORT_DESC],
                        ],
                        'lg_address' => [
                            'asc' => ['lg_address' => SORT_ASC],
                            'desc' => ['lg_address' => SORT_DESC],
                        ],
                        'campaign_id' => [
                            'asc' => ['campaign_id' => SORT_ASC],
                            'desc' => ['campaign_id' => SORT_DESC],
                        ],
                        'defaultOrder' => [$primaryKey => SORT_DESC]
                    ]
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->campaign_id) {
            $query->andFilterWhere(['like', 'ccc.cmp_id', $this->campaign_id]);

        }
        if ($this->lg_first_name) {
            $query->andFilterWhere(['like', 'lg_first_name', $this->lg_first_name]);

        }
        if ($this->lg_last_name) {
            $query->andFilterWhere(['like', 'lg_last_name', $this->lg_last_name]);

        }
        if ($this->lg_contact_number) {
            $query->andFilterWhere(['like', 'lg_contact_number', $this->lg_contact_number]);

        }


        /*if (isset($leadGropIds) && !empty($leadGropIds)) {
            $query->andFilterWhere(['IN', 'ld_id', implode(',', $leadGropIds)]);
        }*/

        /* if (isset($params['CampaignMappingUserSearch']['lg_first_name']) && !empty($params['CampaignMappingUserSearch']['lg_first_name'])) {
             $query->andFilterWhere(['like', 'lg_first_name', $params['CampaignMappingUserSearch']['lg_first_name']]);
         }
         if (isset($params['CampaignMappingUserSearch']['lg_last_name']) && !empty($params['CampaignMappingUserSearch']['lg_last_name'])) {
             $query->andFilterWhere(['like', 'lg_last_name', $params['CampaignMappingUserSearch']['lg_last_name']]);
         }
         if (isset($params['CampaignMappingUserSearch']['lg_contact_number']) && !empty($params['CampaignMappingUserSearch']['lg_contact_number'])) {
             $query->andFilterWhere(['like', 'lg_contact_number', $params['CampaignMappingUserSearch']['lg_contact_number']]);
         }*/
        return $dataProvider;
    }
}
