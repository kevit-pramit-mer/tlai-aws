<?php

namespace app\modules\ecosmob\timeclockreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Cassandra\Time;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;


class TimeClockReportSearch extends TimeClockReport
{
    public $from;
    public $to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['login_time', 'logout_time', 'created_at', 'campaign_name', 'from', 'to'], 'safe'],
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
                  return $('#calltimedistributionsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#calltimedistributionsearch-to').val() != '' ;
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
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        if(isset($params['TimeClockReportSearch'])) {
            if (!empty($params['TimeClockReportSearch']['from'])) {
                $from = $params['TimeClockReportSearch']['from'];
            }
            if (!empty($params['TimeClockReportSearch']['to'])) {
                $to = $params['TimeClockReportSearch']['to'];
            }
        }

        $users = [];

        if(Yii::$app->user->identity->adm_is_admin == 'supervisor'){
            $supCamp = CampaignMappingUser::find()->select(['campaign_id'])->where(['supervisor_id' => Yii::$app->user->identity->id])->asArray()->all();
            $superCamp = array_column($supCamp, 'campaign_id');
            $agent = CampaignMappingAgents::find()->select(['agent_id'])->where(['IN', 'campaign_id', $superCamp])->asArray()->all();
            $agents = array_column($agent, 'agent_id');
            $user = UsersActivityLog::find()->select('user_id')->andWhere(['>=', 'DATE(login_time)', $from])
                ->andWhere(['<=', 'DATE(login_time)', $to])->andWhere(['IN', 'user_id', $agents])->groupBy('user_id')->all();
            if (!empty($user)) {
                foreach ($user as $_user) {
                    $users[] = $_user->user_id;
                }
            }
        }else {
            $user = UsersActivityLog::find()->select('user_id')->andWhere(['>=', 'DATE(login_time)', $from])
                ->andWhere(['<=', 'DATE(login_time)', $to])->groupBy('user_id')->all();
            if (!empty($user)) {
                foreach ($user as $_user) {
                    $users[] = $_user->user_id;
                }
            }
        }

        $query = TimeClockReport::find()
            ->select([
                'am.adm_id as user_id',
                'CONCAT(am.adm_firstname, " ", am.adm_lastname) as agent',
                'login_time' => TimeClockReport::find()
                    ->select('login_time')
                    ->andWhere(['>=', 'DATE(login_time)',  $from])
                    ->andWhere(['<=', 'DATE(login_time)',  $to])
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_ASC])
                    ->limit(1),
                'logout_time' => TimeClockReport::find()
                    ->select('logout_time')
                    ->andWhere(['>=', 'DATE(login_time)',  $from])
                    ->andWhere(['<=', 'DATE(login_time)',  $to])
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_DESC])
                    ->limit(1),
                'total_break_time' => BreakReasonMapping::find()
                    ->select(['SUM(TIMESTAMPDIFF(SECOND, in_time, out_time))'])
                    ->andWhere(['>=', 'DATE(in_time)',  $from])
                    ->andWhere(['<=', 'DATE(out_time)',  $to])
                    ->andWhere('user_id = am.adm_id'),
                'total_breaks' => BreakReasonMapping::find()
                    ->select(['count(id)'])
                    ->andWhere(['>=', 'DATE(in_time)',  $from])
                    ->andWhere(['<=', 'DATE(out_time)',  $to])
                    ->andWhere('user_id = am.adm_id'),
            ])
            ->from('admin_master am')
            ->andWhere(['am.adm_status' => '1'])
            ->andWhere(['IN', 'am.adm_is_admin', ['agent', 'supervisor']])
            ->andWhere(['IN', 'am.adm_id', $users])
            ->groupBy(['am.adm_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ["agent" => SORT_ASC],
                'attributes' => [
                    'agent' => [
                        'asc' => ['agent' => SORT_ASC],
                        'desc' => ['agent' => SORT_DESC],
                    ],
                    'login_time' => [
                        'asc' => ['login_time' => SORT_ASC],
                        'desc' => ['login_time' => SORT_DESC],
                    ],
                    'logout_time' => [
                        'asc' => ['logout_time' => SORT_ASC],
                        'desc' => ['logout_time' => SORT_DESC],
                    ],
                    'total_break_time' => [
                        'asc' => ['total_break_time' => SORT_ASC],
                        'desc' => ['total_break_time' => SORT_DESC],
                    ],
                    'total_breaks' => [
                        'asc' => ['total_breaks' => SORT_ASC],
                        'desc' => ['total_breaks' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'am.adm_id' => $this->user_id,
        ]);

        return $dataProvider;
    }

}
