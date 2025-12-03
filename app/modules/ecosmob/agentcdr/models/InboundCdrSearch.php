<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 28/9/18
 * Time: 2:33 PM
 */

namespace app\modules\ecosmob\agentcdr\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\agentcdr\models\InboundCdr;
use yii\mongodb\ActiveQuery;

class InboundCdrSearch extends InboundCdr
{
    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    "main_uuid",
                    "dialed_number",
                    "caller_id_number",
                    "user_id",
                    "sp_id",
                    "user_name",
                    "sp_name",
                    "outpluse_caller_id_number",
                    "outpluse_dialed_number",
                    "did_type",
                    "did_id",
                    "flat_rate",
                    "free_min",
                    "billed_min",
                    "cost",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "trunk_id",
                    "trunk_name",
                    "direction",
                    "duration",
                    "billsec",
                    "forward_by",
                    "hangup"
                ],
                'safe',
            ],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        if (Yii::$app->user->identity->user_type == "admin") {
            $query = InboundCdr::find();
        }
        if (Yii::$app->user->identity->user_type == "service_provider") {
            $query = InboundCdr::find()->where(['sp_id' => (string)Yii::$app->user->identity->user_id]);
        }
        if (Yii::$app->user->identity->user_type == "customer") {
            $query = InboundCdr::find()->where(['user_id' => (string)Yii::$app->user->identity->user_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere(['like', 'outpluse_dialed_number', $this->outpluse_dialed_number])
            ->andFilterWhere(['like', 'caller_id_number', $this->caller_id_number])
            ->andFilterWhere(['like', 'callstatus', $this->callstatus])
            ->andFilterWhere(['like', 'main_uuid', $this->main_uuid])
            //   ->andFilterWhere(['like', 'direction', $this->direction])
            ->andFilterWhere(['like', 'call_type', $this->call_type])
            ->andFilterWhere(['like', 'sp_name', $this->sp_name])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);
        //   ->andFilterWhere(['like', 'fcd_notify_admin', $this->fcd_notify_admin]
        //);

        foreach (['start_epoch', 'answer_epoch', 'end_epoch'] as $epoch) {
            $date = explode(' - ', $this->{$epoch});

            if (isset($date[0]) && isset($date[1]) &&
                Yii::$app->helper->validateDate($date[0], 'Y-m-d H:i:s') && Yii::$app->helper->validateDate($date[1],
                    'Y-m-d H:i:s')
            ) {
                $query->andFilterWhere(['>=', $epoch, (string)Yii::$app->helper->dtToTs($date[0], 'Y-m-d H:i:s')]);
                $query->andFilterWhere(['<=', $epoch, (string)Yii::$app->helper->dtToTs($date[1], 'Y-m-d H:i:s')]);
            } /*else {
                if ($epoch == 'start_epoch') {
                    $query->andFilterWhere(['>=', $epoch, (string)Yii::$app->helper->dtToTs(date('Y-m-d 00:00:00'))]);
                    $query->andFilterWhere(['<=', $epoch, (string)Yii::$app->helper->dtToTs(date('Y-m-d 23:59:59'))]);
                }
            }*/
            unset($date);
        }
        return $dataProvider;
    }
}
