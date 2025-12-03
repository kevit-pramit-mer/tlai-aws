<?php

namespace app\modules\ecosmob\extensionsummaryreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class CdrSearch extends Cdr
{
    public $internal_call;

    public function rules()
    {
        return [
            [
                [
                    "uuid",
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
                    'internal_call',
                    'call_id',
                    'service_type'
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
        $extension = Extension::find()->select(['em_extension_number', 'external_caller_id'])->asArray()->all();
        $extNumber = array_column($extension, 'em_extension_number');
        $extCallerId = array_filter(array_column($extension, 'external_caller_id'));
        $extNum = array_unique(array_merge($extNumber, $extCallerId));
        /*$query = Cdr::find();
        // add conditions that should always apply here
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

        if ($this->start_epoch && $this->end_epoch) {
            $from = strtotime(CommonHelper::DtTots($this->start_epoch));
            $to = strtotime(CommonHelper::DtTots($this->end_epoch));
            $query->andFilterWhere(['>=', 'start_epoch', trim($from)]);
            $query->andFilterWhere(['<=', 'start_epoch', trim($to)]);
        }

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', "direction", $this->direction])
            ->andFilterWhere(['like', "caller_id_number", $this->caller_id_number])
            ->andFilterWhere(['like', "dialed_number", $this->dialed_number]);

*/
        $query = Cdr::find()->andWhere(['OR', ['IN', "caller_id_number", $extNum], ['IN', "dialed_number", $extNum]]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $this->load($params);
        if ($this->start_epoch && $this->end_epoch) {
            $from = strtotime(CommonHelper::DtTots($this->start_epoch));
            $to = strtotime(CommonHelper::DtTots($this->end_epoch));
            $query->andFilterWhere(['>=', 'start_epoch', trim($from)]);
            $query->andFilterWhere(['<=', 'start_epoch', trim($to)]);
        }

        if ($this->caller_id_number) {
            $extNumber = Extension::find()->select(['em_extension_number', 'external_caller_id'])->where(['em_extension_number' => $this->caller_id_number])->one();
            $extNum = [];
            $extNum[] = $extNumber->em_extension_number;
            $extNum[] = $extNumber->external_caller_id;
            $query->andFilterWhere(['OR', ['IN', "caller_id_number", $extNum], ['IN', "dialed_number", $extNum]]);

        }
        // echo '<pre>';
//print_R($dataProvider->getModels());exit;
        // Prepare summary data
        $summaryData = [];
        foreach ($dataProvider->getModels() as $model) {
            $callId = Extension::find()->select(['em_extension_name', 'em_extension_number'])->where(['em_extension_number' => $model->caller_id_number])->one();
            if (empty($callId)) {
                $callId = Extension::find()->select(['em_extension_name', 'em_extension_number'])->where(['external_caller_id' => $model->caller_id_number])->one();
            }
            if (!empty($callId)) {
                $callIdExt = $callId->em_extension_number;

                if (in_array($callIdExt, $extNum) && $model->direction === 'INCOMING') {
                    if (!isset($summaryData[$callIdExt])) {
                        $summaryData[$callIdExt] = [
                            'extension' => $callIdExt,
                            'extension_name' => $callId->em_extension_name,
                            'total_calls' => 0,
                            'total_duration' => 0,
                            'total_answered_calls' => 0,
                            'total_abandoned_calls' => 0,
                            'total_inbound_calls' => 0,
                            'total_outbound_calls' => 0,
                            'total_inbound_call_duration' => 0,
                            'total_outbound_call_duration' => 0,
                        ];
                    }

                    $summaryData[$callIdExt]['total_calls']++;
                    $summaryData[$callIdExt]['total_duration'] += intval($model->duration);
                    if ($model->answer_epoch != "0") {
                        $summaryData[$callIdExt]['total_answered_calls']++;
                    } elseif ($model->answer_epoch == "0") {
                        $summaryData[$callIdExt]['total_abandoned_calls']++;
                    }
                    $summaryData[$callIdExt]['total_inbound_calls']++;
                    $summaryData[$callIdExt]['total_inbound_call_duration'] += intval($model->duration);
                }
            }

            $dialNum = Extension::find()->select(['em_extension_name', 'em_extension_number'])->where(['em_extension_number' => $model->dialed_number])->one();
            if (empty($dialNum)) {
                $dialNum = Extension::find()->select(['em_extension_name', 'em_extension_number'])->where(['external_caller_id' => $model->dialed_number])->one();
            }
            if (!empty($dialNum)) {
                $dialNUmberExt = $dialNum->em_extension_number;

                if (in_array($dialNUmberExt, $extNum) && $model->direction === 'OUTGOING') {
                    if (!isset($summaryData[$dialNUmberExt])) {
                        $summaryData[$dialNUmberExt] = [
                            'extension' => $dialNUmberExt,
                            'extension_name' => $dialNum->em_extension_name,
                            'total_calls' => 0,
                            'total_duration' => 0,
                            'total_answered_calls' => 0,
                            'total_abandoned_calls' => 0,
                            'total_inbound_calls' => 0,
                            'total_outbound_calls' => 0,
                            'total_inbound_call_duration' => 0,
                            'total_outbound_call_duration' => 0,
                        ];
                    }

                    $summaryData[$dialNUmberExt]['total_calls']++;
                    $summaryData[$dialNUmberExt]['total_duration'] += intval($model->duration);
                    if ($model->answer_epoch != "0") {
                        $summaryData[$dialNUmberExt]['total_answered_calls']++;
                    } elseif ($model->answer_epoch == "0") {
                        $summaryData[$dialNUmberExt]['total_abandoned_calls']++;
                    }
                    $summaryData[$dialNUmberExt]['total_outbound_calls']++;
                    $summaryData[$dialNUmberExt]['total_outbound_call_duration'] += intval($model->duration);
                }
            }
        }
        //exit;
        // Calculate average call duration
        foreach ($summaryData as &$summaryItem) {
            $summaryItem['average_call_duration'] = $summaryItem['total_calls'] > 0 ? $summaryItem['total_duration'] / $summaryItem['total_calls'] : 0;
        }

        // Create a data provider for summary data
        $summaryDataProvider = new ArrayDataProvider([
            'allModels' => array_values($summaryData),
            'sort' => [
                'attributes' => [
                    'extension' => [
                        'asc' => ['extension' => SORT_ASC],
                        'desc' => ['extension' => SORT_DESC],
                    ],
                    'extension_name' => [
                        'asc' => ['extension_name' => SORT_ASC],
                        'desc' => ['extension_name' => SORT_DESC],
                    ],
                    'total_calls' => [
                        'asc' => ['total_calls' => SORT_ASC],
                        'desc' => ['total_calls' => SORT_DESC],
                    ],
                    'total_duration' => [
                        'asc' => ['total_duration' => SORT_ASC],
                        'desc' => ['total_duration' => SORT_DESC],
                    ],
                    'average_call_duration' => [
                        'asc' => ['average_call_duration' => SORT_ASC],
                        'desc' => ['average_call_duration' => SORT_DESC],
                    ],
                    'total_answered_calls' => [
                        'asc' => ['total_answered_calls' => SORT_ASC],
                        'desc' => ['total_answered_calls' => SORT_DESC],
                    ],
                    'total_abandoned_calls' => [
                        'asc' => ['total_abandoned_calls' => SORT_ASC],
                        'desc' => ['total_abandoned_calls' => SORT_DESC],
                    ],
                    'total_inbound_calls' => [
                        'asc' => ['total_inbound_calls' => SORT_ASC],
                        'desc' => ['total_inbound_calls' => SORT_DESC],
                    ],
                    'total_inbound_call_duration' => [
                        'asc' => ['total_inbound_call_duration' => SORT_ASC],
                        'desc' => ['total_inbound_call_duration' => SORT_DESC],
                    ],
                    'total_outbound_calls' => [
                        'asc' => ['total_outbound_calls' => SORT_ASC],
                        'desc' => ['total_outbound_calls' => SORT_DESC],
                    ],
                    'total_outbound_call_duration' => [
                        'asc' => ['total_outbound_call_duration' => SORT_ASC],
                        'desc' => ['total_outbound_call_duration' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        return $summaryDataProvider;
    }
}
