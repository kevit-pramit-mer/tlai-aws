<?php

namespace app\modules\ecosmob\faxdetailsreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\faxdetailsreport\FaxDetailsReportModule;
use yii\mongodb\ActiveRecord;

class Cdr extends ActiveRecord
{

    //public $isfile;

    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'uctenat.fax.cdr'];
    }

    public static function allColumns()
    {
        return [

            'fax_caller' =>
                [
                    'attribute' => 'fax_caller',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->fax_caller == NULL
                            ?
                            "N/A"
                            : ($model->fax_caller == ""
                                ? "-"
                                :
                                $model->fax_caller));
                    },
                ],
            'fax_callee' =>
                [
                    'attribute' => 'fax_callee',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->fax_callee == NULL
                            ?
                            "N/A"
                            : ($model->fax_callee == ""
                                ? "-"
                                :
                                $model->fax_callee));
                    },
                ],
            'direction' =>
                [
                    'attribute' => 'direction',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->direction == NULL
                            ?
                            "N/A"
                            : ($model->direction == ""
                                ? "N/A"
                                :
                                $model->direction));
                    },
                    'enableSorting' => True,
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
            'fax_total' =>
                [
                    'attribute' => 'fax_total',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->fax_total == NULL
                            ?
                            "N/A"
                            : ($model->fax_total == ""
                                ? "N/A"
                                :
                                $model->fax_total));
                    },
                ],
            'faxstatus' =>
                [
                    'attribute' => 'faxstatus',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->faxstatus == NULL
                            ?
                            "-"
                            : ($model->faxstatus == ""
                                ? "-"
                                :
                                $model->faxstatus));
                    },
                ],
            'hangup_cause' =>
                [
                    'attribute' => 'hangup_cause',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->hangup_cause == null ? "N/A" : ($model->hangup_cause == "" ? "N/A" :
                            (gettype($model->hangup_cause) == 'string' ? $model->hangup_cause : "-")));
                    }
                ],
        ];
    }

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
                    'faxstatus',
                    "fax_type",
                    "fax_format",
                    "hangup_cause"
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
            "fax_caller",
            "fax_callee",
            'direction',
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "duration",
            "billsec",
            "fax_total",
            "faxstatus",
            "fax_type",
            "fax_format",
            "hangup_cause",
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => FaxDetailsReportModule::t('cdr', 'uuid'),
            'sip_call_id' => FaxDetailsReportModule::t('cdr', 'sip_call_id'),
            'dialed_number' => FaxDetailsReportModule::t('cdr', 'dialed_number'),
            'caller_id_number' => FaxDetailsReportModule::t('cdr', 'caller_id_number'),
            'isfile' => FaxDetailsReportModule::t('cdr', 'isfile'),
            'call_type' => FaxDetailsReportModule::t('cdr', 'call_type'),
            'start_epoch' => FaxDetailsReportModule::t('cdr', 'start_epoch'),
            'answer_epoch' => FaxDetailsReportModule::t('cdr', 'answer_epoch'),
            'end_epoch' => FaxDetailsReportModule::t('cdr', 'end_epoch'),
            'callstatus' => FaxDetailsReportModule::t('cdr', 'callstatus'),
            'direction' => FaxDetailsReportModule::t('cdr', 'direction'),
            'duration' => FaxDetailsReportModule::t('cdr', 'duration'),
            'billsec' => FaxDetailsReportModule::t('cdr', 'billsec'),
            'ext_call' => FaxDetailsReportModule::t('cdr', 'ext_call'),
            'trunk_id' => FaxDetailsReportModule::t('cdr', 'trunk_id'),
            'trunk_name' => FaxDetailsReportModule::t('cdr', 'trunk_name'),
            'fax_caller' => FaxDetailsReportModule::t('cdr', 'fax_caller'),
            'fax_callee' => FaxDetailsReportModule::t('cdr', 'fax_callee'),
            'fax_total' => FaxDetailsReportModule::t('cdr', 'fax_total'),
            'faxstatus' => FaxDetailsReportModule::t('cdr', 'faxstatus'),
            'hangup_cause' => FaxDetailsReportModule::t('cdr', 'hangup'),

        ];
    }
}
