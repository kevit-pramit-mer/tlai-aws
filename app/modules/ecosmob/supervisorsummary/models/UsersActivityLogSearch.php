<?php

namespace app\modules\ecosmob\supervisorsummary\models;

use app\components\CommonHelper;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * UsersActivityLogSearch represents the model behind the search form of `app\modules\ecosmob\supervisorsummary\models\UsersActivityLog`.
 */
class UsersActivityLogSearch extends UsersActivityLog
{
    public $from;
    public $to;
    public $user_type;
    public $campaign;
    public $date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['login_time', 'logout_time', 'campaign_name', 'created_at', 'from', 'to', 'user_type', 'in_time', 'campaign', 'date'], 'safe'],
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
                  return $('#usersactivitylogsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#usersactivitylogsearch-to').val() != '' ;
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
        $supervisorCamp = CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }


        if (Yii::$app->session->get('loginAsExtension')) {
            $extensionModel = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            $timeZoneModel = Timezone::findOne(['tz_id' => $extensionModel->em_timezone_id]);
        } else {
            $timeZoneModel = Timezone::findOne(['tz_id' => Yii::$app->user->identity->adm_timezone_id]);
        }

        $getTimezoneName = $timeZoneModel->tz_zone;

        $query = UsersActivityLog::find()
            ->select(['adp.user_id', 'ccc.cmp_name as campaign_name', 'am.adm_firstname', 'am.adm_lastname', 'am.adm_is_admin',
                'adp.login_time', 'adp.logout_time',
                /* 'SUM(TIMESTAMPDIFF(SECOND, adp.login_time, adp.logout_time)) login_time', */
                /*'DATE_FORMAT(adp.login_time,\'%H:%i:%s\') login_time',
                'DATE_FORMAT(adp.logout_time,\'%H:%i:%s\') logout_time',*/
                'break_time' => BreakReasonMapping::find()
                    ->select(['SUM(TIMESTAMPDIFF(SECOND, in_time, out_time))'])
                    ->andWhere('user_id = adp.user_id')
                    ->andWhere(new Expression('FIND_IN_SET(adp.campaign_name, camp_id)'))
                    ->andWhere('in_time > adp.login_time AND out_time < (CASE WHEN adp.logout_time = "0000-00-00 00:00:00" THEN "'.date('Y-m-d H:i:s').'" ELSE adp.logout_time END)')
                    //'SUM(TIMESTAMPDIFF(SECOND, brm.in_time, brm.out_time)) break_time',
            ])
            ->from('users_activity_log adp')
            ->leftJoin('admin_master am', 'am.adm_id=adp.user_id')
            ->leftJoin('ct_call_campaign ccc', 'ccc.cmp_id=adp.campaign_name')
            //->leftJoin('break_reason_mapping brm', 'brm.user_id=adp.user_id AND DATE_FORMAT(adp.login_time, "%Y-%m-%d") = DATE_FORMAT(brm.in_time, "%Y-%m-%d") AND adp.campaign_name = brm.camp_id')
            //->where(['NOT IN', 'adp.logout_time', '0000-00-00 00:00:00'])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->groupby(['adp.id', 'DATE_FORMAT(adp.login_time, "%Y-%m-%d")', 'adp.campaign_name'])
            //->groupby(['adp.user_id', 'DATE_FORMAT(adp.login_time, "%Y-%m-%d")','adp.campaign_name'])
            //->orderby(['DATE_FORMAT(adp.login_time, "%Y-%m-%d")' => SORT_DESC])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC, 'login_time' => SORT_DESC],
                'attributes' => [
                    'adm_firstname' => [
                        'asc' => ['am.adm_firstname' => SORT_ASC],
                        'desc' => ['am.adm_firstname' => SORT_DESC],
                    ],
                    'date' => [
                        'asc' => ['DATE_FORMAT(adp.login_time, "%Y-%m-%d")' => SORT_ASC],
                        'desc' => ['DATE_FORMAT(adp.login_time, "%Y-%m-%d")' => SORT_DESC],
                    ],
                    'login_time' => [
                        'asc' => ["DATE_FORMAT(CONVERT_TZ( adp.login_time, 'UTC', '" . $getTimezoneName . "' ), '%H:%i:%s')" => SORT_ASC],
                        'desc' => ["DATE_FORMAT(CONVERT_TZ( adp.login_time, 'UTC', '" . $getTimezoneName . "' ), '%H:%i:%s')" => SORT_DESC],
                    ],
                    'logout_time' => [
                        'asc' => ["DATE_FORMAT(CONVERT_TZ( adp.logout_time, 'UTC', '" . $getTimezoneName . "' ), '%H:%i:%s')" => SORT_ASC],
                        'desc' => ["DATE_FORMAT(CONVERT_TZ( adp.logout_time, 'UTC', '" . $getTimezoneName . "' ), '%H:%i:%s')" => SORT_DESC],
                    ],
                    'break_time' => [
                        'asc' => ['break_time' => SORT_ASC],
                        'desc' => ['break_time' => SORT_DESC],
                    ],
                    'campaign_name' => [
                        'asc' => ['campaign_name' => SORT_ASC],
                        'desc' => ['campaign_name' => SORT_DESC],
                    ],
                ]
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->from && $this->to) {
            $from = CommonHelper::DtTots($this->from.' 00:00:01', 'Y-m-d');
            $to = CommonHelper::DtTots($this->to.' 23:59:59', 'Y-m-d');
            $query->andFilterWhere(['>=', 'DATE(adp.login_time)', trim($this->from)]);
            $query->andFilterWhere(['<=', 'DATE(adp.login_time)', trim($this->to)]);
        }

        if ($this->campaign) {
            $query->andFilterWhere(['=', 'campaign_name', $this->campaign]);
        }

        $query->andFilterWhere(['like', 'am.adm_is_admin', $this->user_type]);

        return $dataProvider;
    }
}
