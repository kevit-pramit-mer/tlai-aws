<?php

namespace app\modules\ecosmob\faxdetailsreport\models;

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
                    "fax_caller",
                    "fax_callee",
                    'direction',
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "duration",
                    "billsec",
                    "fax_total",
                    "fax_trans",
                    "hangup_cause",
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
        $query = Cdr::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->start_epoch && $this->end_epoch) {
            $from = strtotime(CommonHelper::DtTots($this->start_epoch));
            $to = strtotime(CommonHelper::DtTots($this->end_epoch));
            $query->andFilterWhere(['>=', 'start_epoch', trim($from)]);
            $query->andFilterWhere(['<=', 'start_epoch', trim($to)]);
        }

        $query->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }

}
