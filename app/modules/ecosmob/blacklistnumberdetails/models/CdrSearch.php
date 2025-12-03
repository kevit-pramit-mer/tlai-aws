<?php

namespace app\modules\ecosmob\blacklistnumberdetails\models;

use app\components\CommonHelper;
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
        $query = Cdr::find()->Where(['LIKE', 'hangup', 'Blacklist']);
        $query->andWhere(['!=', "caller_id_number", '*99']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (Yii::$app->session->get('loginAsExtension')) {
            $query->andFilterWhere(['caller_id_number' => Yii::$app->user->identity->em_extension_number]);
        } else {
            $query->andFilterWhere(['like', "caller_id_number", $this->caller_id_number]);
        }

        if ($this->answer_epoch) {
            $start_time = strtotime(CommonHelper::DtTots($this->answer_epoch));
            $end_time = strtotime(CommonHelper::DtTots($this->answer_epoch));
            $query->andFilterWhere(['>=', 'answer_epoch', trim($start_time)]);
            $query->andFilterWhere(['<=', 'answer_epoch', trim($end_time)]);
        }

        if ($this->start_epoch && $this->end_epoch) {
            $from = strtotime(CommonHelper::DtTots($this->start_epoch));
            $to = strtotime(CommonHelper::DtTots($this->end_epoch));
            $query->andFilterWhere(['>=', 'start_epoch', trim($from)]);
            $query->andFilterWhere(['<=', 'start_epoch', trim($to)]);
        }

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', "dialed_number", $this->dialed_number])
            ->andFilterWhere(['like', "sip_call_id", $this->sip_call_id])
            ->andFilterWhere(['like', "call_type", $this->call_type])
            ->andFilterWhere(['like', "callstatus", $this->callstatus]);

        return $dataProvider;
    }
}
