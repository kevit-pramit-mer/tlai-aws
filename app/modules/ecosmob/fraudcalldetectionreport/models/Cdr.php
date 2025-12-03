<?php

namespace app\modules\ecosmob\fraudcalldetectionreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\fraudcalldetectionreport\FraudCallDetectionReportModule;
use yii\mongodb\ActiveRecord;

class Cdr extends ActiveRecord
{

    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'call.fraud'];
    }

    public static function allColumns()
    {
        return [

            'caller_id_number' =>
                [
                    'attribute' => 'caller_id_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->caller_id_number == NULL
                            ?
                            "N/A"
                            : ($model->caller_id_number == ""
                                ? "-"
                                :
                                $model->caller_id_number));
                    },
                ],
            'dialed_number' =>
                [
                    'attribute' => 'dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->dialed_number == NULL
                            ?
                            "N/A"
                            : ($model->dialed_number == ""
                                ? "-"
                                :
                                $model->dialed_number));
                    },
                ],
            'duration' =>
                [
                    'attribute' => 'duration',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->duration == NULL
                            ?
                            "N/A"
                            : ($model->duration == ""
                                ? "N/A"
                                :
                                $model->duration));
                    },
                    'enableSorting' => True,
                ],
            'start_epoch' =>
                [
                    'attribute' => 'start_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {

                        $utc = $model->start_epoch;
                        $time = date("Y-m-d H:i:s", substr($utc, 0, 10));

                        return ($time == "0") ?
                            "N/A" : CommonHelper::tsToDt($time);
                    },
                ],
            'answer_epoch' =>
                [
                    'attribute' => 'answer_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        $utc = $model->answer_epoch;
                        $time = date("Y-m-d H:i:s", substr($utc, 0, 10));

                        return ($model->answer_epoch == "0") ?
                            "N/A" : CommonHelper::tsToDt($time);
                    },
                ],
            'end_epoch' =>
                [
                    'attribute' => 'end_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        $utc = $model->end_epoch;
                        $time = date("Y-m-d H:i:s", substr($utc, 0, 10));

                        return ($time == "0") ?
                            "N/A" : CommonHelper::tsToDt($time);
                    },
                ],
            'trunk_name' =>
                [
                    'attribute' => 'trunk_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->trunk_name == NULL
                            ?
                            "N/A"
                            : ($model->trunk_name == ""
                                ? "N/A"
                                :
                                $model->trunk_name));
                    },
                    'enableSorting' => True,
                ],
            'trunk_id' =>
                [
                    'attribute' => 'trunk_id',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->trunk_id == NULL
                            ?
                            "N/A"
                            : ($model->trunk_id == ""
                                ? "N/A"
                                :
                                $model->trunk_id));
                    },
                    'enableSorting' => True,
                ],
            'rule_name' =>
                [
                    'attribute' => 'rule_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->rule_name == NULL)
                            ? "N/A"
                            : (($model->rule_name == "")
                                ? "N/A"
                                :
                                $model->rule_name
                            );
                    },
                    'enableSorting' => True,
                ],
            'rule_id' =>
                [
                    'attribute' => 'rule_id',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->rule_id == NULL)
                            ? "N/A"
                            : (($model->rule_id == "")
                                ? "N/A"
                                :
                                $model->rule_id
                            );
                    },
                    'enableSorting' => True,
                ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    "dialed_number",
                    "caller_id_number",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "duration",
                    "billsec",
                    "trunk_id",
                    "trunk_name",
                    "rule_id",
                    "rule_name",
                ],
                'safe',
            ],
        ];
    }

    public function attributes()
    {
        return [
            "_id",
            "uuid",
            "dialed_number",
            "caller_id_number",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "duration",
            "trunk_id",
            "trunk_name",
            "rule_id",
            "rule_name"
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => FraudCallDetectionReportModule::t('cdr', 'uuid'),
            'sip_call_id' => FraudCallDetectionReportModule::t('cdr', 'sip_call_id'),
            'dialed_number' => FraudCallDetectionReportModule::t('cdr', 'dialed_number'),
            'caller_id_number' => FraudCallDetectionReportModule::t('cdr', 'caller_id_number'),
            'isfile' => FraudCallDetectionReportModule::t('cdr', 'isfile'),
            'call_type' => FraudCallDetectionReportModule::t('cdr', 'call_type'),
            'start_epoch' => FraudCallDetectionReportModule::t('cdr', 'start_epoch'),
            'answer_epoch' => FraudCallDetectionReportModule::t('cdr', 'answer_epoch'),
            'end_epoch' => FraudCallDetectionReportModule::t('cdr', 'end_epoch'),
            'callstatus' => FraudCallDetectionReportModule::t('cdr', 'callstatus'),
            'direction' => FraudCallDetectionReportModule::t('cdr', 'direction'),
            'duration' => FraudCallDetectionReportModule::t('cdr', 'duration'),
            'billsec' => FraudCallDetectionReportModule::t('cdr', 'billsec'),
            'ext_call' => FraudCallDetectionReportModule::t('cdr', 'ext_call'),
            'trunk_id' => FraudCallDetectionReportModule::t('cdr', 'trunk_id'),
            'trunk_name' => FraudCallDetectionReportModule::t('cdr', 'trunk_name'),
            'hangup' => FraudCallDetectionReportModule::t('cdr', 'hangup'),
            'rule_name' => FraudCallDetectionReportModule::t('cdr', 'rule_name'),
            'rule_id' => FraudCallDetectionReportModule::t('cdr', 'rule_id'),
        ];
    }
}
