<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 12:05 PM
 */

namespace app\modules\ecosmob\faxdetailsreport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TransferCdrSearch
 *
 * @package app\modules\ecosmob\cdr\models
 */
class TransferCdrSearch extends Cdr
{
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    "dialed_number",
                    "caller_id_number",
                    "user_id",
                    "sp_id",
                    "user_name", //
                    "sp_name", //
                    "outpluse_caller_id_number",
                    "outpluse_dialed_number",
                    "free_min", //
                    "billed_min", //
                    "sell_cost",
                    "sell_rc_id",
                    "sell_rc_name", //
                    "sell_rate",
                    "sell_min_duration",
                    "buy_cost",
                    "buy_rc_id",
                    "buy_rc_name", //
                    "buy_rate",
                    "buy_min_duration",
                    "service",
                    "package_id",
                    "package_name", //
                    "call_type",
                    "call_region",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "duration",
                    "billsec",
                    "trunk_id",
                    "trunk_name",
                    "forward_to",
                    "hangup"
                ],
                'safe',
            ],
        ];
    }
    
    /**
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * @param $params
     *
     * @return \yii\data\ActiveDataProvider
     * @throws \yii\base\InvalidParamException
     */
    public function search($params)
    {
        if (Yii::$app->user->identity->user_type == "admin") {
            $query = TransferCdr::find();
        }
        if (Yii::$app->user->identity->user_type == "service_provider") {
            $query = TransferCdr::find()->where(['sp_id' => (string)Yii::$app->user->identity->user_id]);
        }
        if (Yii::$app->user->identity->user_type == "customer") {
            $query = TransferCdr::find()->where(['user_id' => (string)Yii::$app->user->identity->user_id]);
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
            ->andFilterWhere(['like', 'call_type', $this->call_type])
            ->andFilterWhere(['like', 'sp_name', $this->sp_name])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        foreach (['start_epoch', 'answer_epoch', 'end_epoch'] as $epoch) {
            $date = explode(' - ', $this->{$epoch});

            if (isset($date[0]) && isset($date[1]) &&
                Yii::$app->helper->validateDate($date[0], 'Y-m-d H:i:s') && Yii::$app->helper->validateDate($date[1],
                    'Y-m-d H:i:s')
            ) {
                $query->andFilterWhere(['>=', $epoch, (string)Yii::$app->helper->dtToTs($date[0], 'Y-m-d H:i:s')]);
                $query->andFilterWhere(['<=', $epoch, (string)Yii::$app->helper->dtToTs($date[1], 'Y-m-d H:i:s')]);
            }
            unset($date);
        }
        return $dataProvider;
    }
}
