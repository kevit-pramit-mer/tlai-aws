<?php

namespace app\modules\ecosmob\crm\models;

use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\script\models\Script;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\crm\models\LeadGroupMember;

/**
 * LeadGroupMemberSearch represents the model behind the search form of `app\modules\ecosmob\crm\models\LeadGroupMember`.
 */
class LeadGroupMemberSearch extends LeadGroupMember
{
    /**
     * {@inheritdoc}
     */
    public $script;

    public function rules()
    {
        return [
            [['lg_id', 'ld_id'], 'integer'],
            [['lg_first_name', 'lg_last_name', 'lg_contact_number', 'lg_contact_number_2', 'lg_email_id', 'lg_address', 'lg_alternate_number', 'lg_pin_code', 'lg_permanent_address', 'lg_dial_status'], 'safe'],
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
        $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign']))?$_SESSION['selectedCampaign']:'0';
        $selectedCampaign=$selectedCampaignData;
        $campaignDataList = Campaign::find()->select(['cmp_lead_group','cmp_script'])->where(['cmp_id'=>$selectedCampaign])/*->andwhere(['cmp_dialer_type'=>'PREVIEW'])*/->one();
        $leadData = $campaignDataList['cmp_lead_group'];
        
        $query = LeadGroupMember::find();

        $primaryKey = LeadGroupMember::primaryKey()[0];

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

       /* $leadData = (isset($leadData) && !empty($leadData))?$leadData:'0';
        $query->andFilterWhere(['=', 'ld_id', $leadData]);*/

        $leadData = (isset($leadData) && !empty($leadData))?'0':$leadData;
        $query->andFilterWhere(['=', 'ld_id', $leadData]);

        return $dataProvider;
    }
    public function scrsearch($params)
    {

        $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign']))?$_SESSION['selectedCampaign']:'0';
        $selectedCampaign=$selectedCampaignData;

        $campaignDataList = Campaign::find()->select(['cmp_lead_group', 'cmp_script'])->where(['cmp_id'=>$selectedCampaign])/*->andwhere(['cmp_dialer_type'=>'PREVIEW'])*/->one();
        $scriptData = $campaignDataList['cmp_script'];
        $query = Script::find()->where(['scr_id'=>$scriptData]);


        // $primaryKey = LeadGroupMember::primaryKey()[0];

        // add conditions that should always apply here

        $scrdataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            //'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $scrdataProvider;
        }

       // $leadData = (isset($leadData) && !empty($leadData))?$leadData:'0';
       // $query->andFilterWhere(['=', 'ld_id', $leadData]);

        return $scrdataProvider;
    }

}
