<?php

namespace app\modules\ecosmob\dispositionreport\models;

use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\data\ActiveDataProvider;
use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\db\Expression;


class DispositionReportSearch extends DispositionReport
{
    public $from;
    public $to;
    public $call_count;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agent_id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'from', 'call_count', 'to', 'recording_file'], 'safe'],
            [
                'to',
                'compare',
                'compareAttribute' => 'from',
                'operator' => '>=',
                'message' => Yii::t('app', 'from_to_date_cmp')
            ],
            [['to'], 'required', 'when' => function ($model) {
                return $model->from != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#agentscallreportsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#agentscallreportsearch-to').val() != '' ;
              }", 'message' => Yii::t('app', 'from_date_req'), 'enableClientValidation' => true],
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
        if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            $supervisorCamp = CampaignMappingUser::find()
                ->where(['supervisor_id' => Yii::$app->user->id])
                ->all();
            foreach ($supervisorCamp as $supervisorCamps) {
                $campaignList[] = $supervisorCamps['campaign_id'];
            }
        }
        $query = DispositionReport::find()
            ->select([
                'camp_cdr.call_disposion_name',
                'count(camp_cdr.id) as call_count',
            ])
            ->from('camp_cdr')
            ->leftjoin('ct_call_campaign ccc', 'ccc.cmp_id = camp_cdr.camp_name')
            //->leftJoin('ct_call_campaign ccc', (new Expression('FIND_IN_SET(camp_cdr.camp_name, ccc.cmp_id)')))
            ->leftjoin('admin_master am', 'am.adm_id = camp_cdr.agent_id');
            if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
                $query->where(['ccc.cmp_id' => $campaignList]);
            }
        $query->groupBy('camp_cdr.call_disposion_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ["call_disposion_name" => SORT_ASC],
                'attributes' => [
                    'call_disposion_name' => [
                        'asc' => ['call_disposion_name' => SORT_ASC],
                        'desc' => ['call_disposion_name' => SORT_DESC],
                    ],
                    'call_count' => [
                        'asc' => ['call_count' => SORT_ASC],
                        'desc' => ['call_count' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->from && $this->to) {
            $from = CommonHelper::DtTots($this->from . ' 00:00:01');
            $to = CommonHelper::DtTots($this->to . ' 23:59:59');
            $query->andFilterWhere(['>=', 'call_disposion_start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'call_disposion_start_time', trim($to)]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'agent_id' => $this->agent_id,
            'ccc.cmp_id' => $this->camp_name,
        ]);

        return $dataProvider;
    }
}
