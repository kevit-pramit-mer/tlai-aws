<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 12:05 PM
 */

namespace app\modules\ecosmob\supervisoragentcdr\models;

use app\modules\ecosmob\extension\extensionModule;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\supervisoragentcdr\models\Cdr;
use yii\mongodb\ActiveQuery;

class CdrSearch extends Cdr
{
    public function rules()
    {
        return [
            [
                [

                    /*"user_name",*/ //done
                    /*"sp_name",*/ //done
                    /*"outpluse_caller_id_number",
                    "outpluse_dialed_number",
                    "free_min", //done
                    "billed_min", //done
                    "sell_cost",
                    "sell_rc_id",
                    "sell_rc_name",//done
                    "sell_rate",
                    "sell_min_duration",
                    "buy_cost",
                    "buy_rc_id",
                    "buy_rc_name", //done
                    "buy_rate",
                    "buy_min_duration",
                    "service",
                    "package_id",
                    "package_name", //done*/
                    /*"call_region",*/
                    "uuid",
                    "dialed_number",
                    "caller_id_number",
                    "sip_call_id",
                    "sp_id",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "direction",
                    "duration",
                    "billsec",
                    "ext_call",
                    "trunk_id",
                    "trunk_name",
                    "hangup",
                    "record_filename",
                    "call_type",
                    "isfile",
                    /*"forward_to",*/
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


        $query=Cdr::find();

        if(isset($_GET['isfile']) && !empty($_GET['isfile'])){
            $query->andFilterWhere(['like', 'record_filename', 'recordings']);
        }


        // add conditions that should always apply here

        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'sort'=>['defaultOrder'=>['start_epoch'=>SORT_DESC]],
            'pagination'=>['pageSize'=>Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if (Yii::$app->session->get('loginAsExtension')) {
            $query->andFilterWhere(['caller_id_number' => Yii::$app->user->identity->em_extension_number]);
        }
        else{
            $query->andFilterWhere(['like', "caller_id_number", $this->caller_id_number]);

        }
        

        if ($this->start_epoch) {
            $start_time = strtotime($this->start_epoch . '00:00:01');//            2019-09-28 00:00:01
            $end_time = strtotime($this->start_epoch . '23:59:59');//            2019-09-28 23:59:59
            $query->andFilterWhere(['>', 'start_epoch', trim($start_time)]);
            $query->andFilterWhere(['<', 'start_epoch', trim($end_time)]);
        }

       if ($this->answer_epoch) {
            $start_time = strtotime($this->answer_epoch . ' 00:00:01');//            2019-09-28 00:00:01
            $end_time = strtotime($this->answer_epoch . ' 23:59:59');//            2019-09-28 23:59:59
            $query->andFilterWhere(['>=', 'answer_epoch', trim($start_time)]);
            $query->andFilterWhere(['<=', 'answer_epoch', trim($end_time)]);
        }
        if ($this->end_epoch) {
            $start_time = strtotime($this->end_epoch . ' 00:00:01');//            2019-09-28 00:00:01
            $end_time = strtotime($this->end_epoch . ' 23:59:59');//            2019-09-28 23:59:59
            $query->andFilterWhere(['>=', 'end_epoch', trim($start_time)]);
            $query->andFilterWhere(['<=', 'end_epoch', trim($end_time)]);
        }

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', "dialed_number", $this->dialed_number])
            ->andFilterWhere(['like', "sip_call_id", $this->sip_call_id])
            ->andFilterWhere(['like', "call_type", $this->call_type])

//            ->andFilterWhere(['like', "start_epoch", $this->start_epoch])
//            ->andFilterWhere(['like', "answer_epoch", $this->answer_epoch])
//            ->andFilterWhere(['like', "end_epoch", $this->end_epoch])
            ->andFilterWhere(['like', "callstatus", $this->callstatus]);

        if (strtolower($this->isfile) == 'yes') {
            $query->andFilterWhere(['like', 'record_filename', 'recordings']);
        }

        //   ->andFilterWhere(['like', 'fcd_notify_admin', $this->fcd_notify_admin]
        //);

        /*foreach (['start_epoch', 'answer_epoch', 'end_epoch'] as $epoch) {
            $date=explode(' - ', $this->{$epoch});

            if (isset($date[0]) && isset($date[1]) &&
                Yii::$app->helper->validateDate($date[0], 'Y-m-d H:i:s') && Yii::$app->helper->validateDate($date[1],
                    'Y-m-d H:i:s')
            ) {
                $query->andFilterWhere(['>=', $epoch, (string)Yii::$app->helper->dtToTs($date[0], 'Y-m-d H:i:s')]);
                $query->andFilterWhere(['<=', $epoch, (string)Yii::$app->helper->dtToTs($date[1], 'Y-m-d H:i:s')]);
                unset($date);
            }
        }*/
        return $dataProvider;
    }
}
