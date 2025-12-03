<?php

namespace app\modules\ecosmob\blacklistnumberdetails\models;

use app\components\CommonHelper;
use app\modules\ecosmob\blacklistnumberdetails\BlacklistNumberDetailsModule;
use yii\mongodb\ActiveRecord;

class Cdr extends ActiveRecord
{

    public $isfile;

    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'uctenant.cdr'];
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
                            "-"
                            : ($model->dialed_number == ""
                                ? "-"
                                :
                                str_replace("%2B", "+", $model->dialed_number)));
                    },
                ],
            'direction' =>
                [
                    'attribute' => 'direction',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                ],

            'callstatus' =>
                [
                    'attribute' => 'callstatus',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
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
            'billsec' =>
                [
                    'attribute' => 'billsec',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->billsec == NULL
                            ?
                            "N/A"
                            : ($model->billsec == ""
                                ? "N/A"
                                :
                                $model->billsec));
                    },
                    'enableSorting' => True,
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
            'ext_call' =>
                [
                    'attribute' => 'ext_call',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->ext_call == NULL
                            ? "N/A"
                            : ($model->ext_call == ""
                                ? "N/A"
                                :
                                $model->ext_call
                            ));
                    },
                    'enableSorting' => True,
                ],
            'hangup' =>
                [
                    'attribute' => 'hangup',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->hangup == NULL
                            ? "N/A"
                            : ($model->hangup == ""
                                ? "N/A"
                                :
                                $model->hangup
                            ));
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
                    "sip_call_id",
                    "dialed_number",
                    "caller_id_number",
                    "record_filename",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "direction",
                    "duration",
                    "billsec",
                    "trunk_id",
                    "trunk_name",
                    "hangup",
                    "isfile",
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
            "sip_call_id",
            "dialed_number",
            "caller_id_number",
            "record_filename",
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
            "isfile",
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => BlacklistNumberDetailsModule::t('cdr', 'uuid'),
            'sip_call_id' => BlacklistNumberDetailsModule::t('cdr', 'sip_call_id'),
            'dialed_number' => BlacklistNumberDetailsModule::t('cdr', 'dialed_number'),
            'caller_id_number' => BlacklistNumberDetailsModule::t('cdr', 'caller_id_number'),
            'isfile' => BlacklistNumberDetailsModule::t('cdr', 'isfile'),
            'call_type' => BlacklistNumberDetailsModule::t('cdr', 'call_type'),
            'start_epoch' => BlacklistNumberDetailsModule::t('cdr', 'start_epoch'),
            'answer_epoch' => BlacklistNumberDetailsModule::t('cdr', 'answer_epoch'),
            'end_epoch' => BlacklistNumberDetailsModule::t('cdr', 'end_epoch'),
            'callstatus' => BlacklistNumberDetailsModule::t('cdr', 'callstatus'),
            'direction' => BlacklistNumberDetailsModule::t('cdr', 'direction'),
            'duration' => BlacklistNumberDetailsModule::t('cdr', 'duration'),
            'billsec' => BlacklistNumberDetailsModule::t('cdr', 'billsec'),
            'ext_call' => BlacklistNumberDetailsModule::t('cdr', 'ext_call'),
            'trunk_id' => BlacklistNumberDetailsModule::t('cdr', 'trunk_id'),
            'trunk_name' => BlacklistNumberDetailsModule::t('cdr', 'trunk_name'),
            'hangup' => BlacklistNumberDetailsModule::t('cdr', 'hangup'),
        ];
    }
}
