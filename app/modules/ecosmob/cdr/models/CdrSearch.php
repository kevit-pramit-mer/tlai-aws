<?php

namespace app\modules\ecosmob\cdr\models;

use app\components\CommonHelper;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CdrSearch extends Cdr
{
    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    "dialed_number",
                    "caller_id_number",
                    "sip_call_id",
                    "sp_id",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "direction",
                    "callstatus",
                    "duration",
                    "billsec",
                    "ext_call",
                    "trunk_id",
                    "trunk_name",
                    "hangup",
                    "record_filename",
                    "call_type",
                    "isfile",
                    "call_id",
                    "service_type",
                    "original_caller_id"
                ],
                'safe',
            ],
            [
                'end_epoch',
                'compare',
                'compareAttribute' => 'start_epoch',
                'operator' => '>='
            ],
            [['end_epoch'], 'required', 'when' => function ($model) {
                return $model->start_epoch != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#cdrsearch-start_epoch').val() != '' ;
              }", 'enableClientValidation' => true],
            [['start_epoch'], 'required', 'when' => function ($model) {
                return $model->end_epoch != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#cdrsearch-end_epoch').val() != '' ;
              }", 'enableClientValidation' => true],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        if (Yii::$app->session->get('loginAsExtension')) {
            $extension = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            $query = Cdr::find()->where(['original_caller_id' => Yii::$app->user->identity->em_extension_number])
                ->orWhere(['or', ['caller_id_number' => Yii::$app->user->identity->em_extension_number], ['dialed_number' => Yii::$app->user->identity->em_extension_number],
                ]);
        } else {
            $query = Cdr::find();
        }

        $query->andWhere(['!=', "caller_id_number", '*99']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC, 'direction' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['=', "caller_id_number", $this->caller_id_number]);

        if ($this->start_epoch && $this->end_epoch) {
            $from = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->start_epoch))));
            $to = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->end_epoch))));
            $query->andFilterWhere(['>=', 'start_epoch', trim($from)]);
            $query->andFilterWhere(['<=', 'end_epoch', trim($to)]);
        }

     /*   if ($this->answer_epoch) {
            $start_time = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->answer_epoch))));//            2019-09-28 00:00:01
            $end_time = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->answer_epoch))));//            2019-09-28 23:59:59
            $query->andFilterWhere(['>=', 'answer_epoch', trim($start_time)]);
            $query->andFilterWhere(['<=', 'answer_epoch', trim($end_time)]);
        }*/

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['=', "dialed_number", $this->dialed_number])
            ->andFilterWhere(['like', "sip_call_id", $this->sip_call_id])
            ->andFilterWhere(['like', "call_type", $this->call_type])
            ->andFilterWhere(['=', "hangup", $this->hangup])
            ->andFilterWhere(['=', "callstatus", $this->callstatus])
            ->andFilterWhere(['=', "call_id", $this->call_id]);

        if (strtolower($this->isfile) == 'yes') {
            $query->andWhere(['<>', 'record_filename', '']);
        }
        if (strtolower($this->isfile) == 'no') {
            $query->andWhere(['=', 'record_filename', '']);
        }

        return $dataProvider;
    }
}
