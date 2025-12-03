<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 11:10 AM
 */

namespace app\modules\ecosmob\cdr\models;

use app\modules\ecosmob\customer\models\Customer;
use app\modules\ecosmob\serviceprovider\models\ServiceProvider;
use Yii;
use app\modules\ecosmob\cdr\CdrModule;

class TransferCdr extends \yii\mongodb\ActiveRecord
{
    public static function collectionName()
    {
        return ['giptechdb', 'transfer.cdr'];
    }

    public static function allColumns()
    {
        return [
            /*'uuid' =>
               [
                   'attribute' => 'uuid',
                   'headerOptions' => ['class' => 'text-center'],
                   'contentOptions' => ['class' => 'text-center'],
               ],*/
            /* 'caller_id_number' =>
                 [
                     'attribute' => 'caller_id_number',
                     'headerOptions' => ['class' => 'text-center'],
                     'contentOptions' => ['class' => 'text-center'],
                 ],*/
            'outpluse_dialed_number' =>
                [
                    'attribute' => 'outpluse_dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
            'outpluse_caller_id_number' =>
                [
                    'attribute' => 'outpluse_caller_id_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
            /*'dialed_number' =>
                [
                    'attribute' => 'dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/
            'user_name' =>
                [
                    'attribute' => 'user_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
            'sp_name' =>
                [
                    'attribute' => 'sp_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],

            'callstatus' =>
                [
                    'attribute' => 'callstatus',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
            'start_epoch' =>
                [
                    'attribute' => 'start_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->start_epoch == "0") ?
                            "N/A" : Yii::$app->helper->tsToDt($model->start_epoch);
                    }
                ],
            'answer_epoch' =>
                [
                    'attribute' => 'answer_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->answer_epoch == "0") ?
                            "N/A" : Yii::$app->helper->tsToDt($model->answer_epoch);
                    }
                ],
            'end_epoch' =>
                [
                    'attribute' => 'end_epoch',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->end_epoch == "0") ?
                            "N/A" : Yii::$app->helper->tsToDt($model->end_epoch);
                    }
                ],
            'call_type' =>
                [
                    'attribute' => 'call_type',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->call_type == null) ?
                            "N/A" : ($model->call_type == "") ? "N/A" :
                                $model->call_type;
                    }
                ],
            'service' =>
                [
                    'attribute' => 'service',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->service == null) ?
                            "N/A" : ($model->service == "") ? "N/A" :
                                $model->service;
                    }
                ],
            'package_name' =>
                [
                    'attribute' => 'package_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->package_name == null) ?
                            "N/A" : ($model->package_name == "") ? "N/A" :
                                $model->package_name;
                    }
                ],
            'duration' =>
                [
                    'attribute' => 'duration',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->duration == null) ?
                            "N/A" : ($model->duration == "") ? "N/A" :
                                $model->duration;
                    }
                ],
            'billsec' =>
                [
                    'attribute' => 'billsec',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->billsec == null) ?
                            "N/A" : ($model->billsec == "") ? "N/A" :
                                $model->billsec;
                    }
                ],
            'free_min' =>
                [
                    'attribute' => 'free_min',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->free_min == null) ?
                            "N/A" : ($model->free_min == "") ? "N/A" :
                                $model->free_min;
                    }
                ],
            'billed_min' =>
                [
                    'attribute' => 'billed_min',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->billed_min == null) ?
                            "N/A" : ($model->billed_min == "") ? "N/A" :
                                $model->billed_min;
                    }
                ],
            'sell_cost' =>
                [
                    'attribute' => 'sell_cost',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->sell_cost == null) ?
                            "N/A" : ($model->sell_cost == "") ? "N/A" :
                                $model->sell_cost;
                    }
                ],
            'sell_rc_name' =>
                [
                    'attribute' => 'sell_rc_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->sell_rc_name == null) ?
                            "N/A" : ($model->sell_rc_name == "") ? "N/A" :
                                $model->sell_rc_name;
                    }
                ],
            'sell_rate' =>
                [
                    'attribute' => 'sell_rate',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->sell_rate == null) ?
                            "N/A" : ($model->sell_rate == "") ? "N/A" :
                                $model->sell_rate;
                    }
                ],
            'sell_min_duration' =>
                [
                    'attribute' => 'sell_min_duration',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->sell_min_duration == null) ?
                            "N/A" : ($model->sell_min_duration == "") ? "N/A" :
                                $model->sell_min_duration;
                    }
                ],
            'buy_cost' =>
                [
                    'attribute' => 'buy_cost',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->buy_cost == null) ?
                            "N/A" : ($model->buy_cost == "") ? "N/A" :
                                $model->buy_cost;
                    }
                ],
            'buy_rc_name' =>
                [
                    'attribute' => 'buy_rc_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->buy_rc_name == null) ?
                            "N/A" : ($model->buy_rc_name == "") ? "N/A" :
                                $model->buy_rc_name;
                    }
                ],
            'buy_rate' =>
                [
                    'attribute' => 'buy_rate',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->buy_rate == null) ?
                            "N/A" : ($model->buy_rate == "") ? "N/A" :
                                $model->buy_rate;
                    }
                ],
            'buy_min_duration' =>
                [
                    'attribute' => 'buy_min_duration',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->buy_min_duration == null) ?
                            "N/A" : ($model->buy_min_duration == "") ? "N/A" :
                                $model->buy_min_duration;
                    }
                ],
            'call_region' =>
                [
                    'attribute' => 'call_region',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->call_region == null) ?
                            "N/A" : ($model->call_region == "") ? "N/A" :
                                $model->call_region;
                    }
                ],
            'trunk_name' =>
                [
                    'attribute' => 'trunk_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->trunk_name == null) ?
                            "N/A" : ($model->trunk_name == "") ? "N/A" :
                                $model->trunk_name;
                    }
                ],
            'forward_to' =>
                [
                    'attribute' => 'forward_to',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->forward_to == null) ?
                            "N/A" : ($model->forward_to == "") ? "N/A" :
                                $model->forward_to;
                    }
                ],
            'hangup' =>
                [
                    'attribute' => 'hangup',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->hangup == null) ? "N/A" : (($model->hangup == "") ? "N/A" :
                            $model->hangup
                        );
                    }
                ],
            /*'hangup' =>
                [
                    'attribute' => 'hangup',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->hangup == null) ? "N/A" : (($model->hangup == "") ? "N/A" :
                            (gettype($model->hangup) == 'string') ? $model->hangup : "-"
                        );
                    }
                ],*/
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

    public function attributes()
    {
        return [
            "_id",
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
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => CdrModule::t('cdr', 'uuid'),
            'dialed_number' => CdrModule::t('cdr', 'dialed_number'),
            'caller_id_number' => CdrModule::t('cdr', 'caller_id_number'),
            'user_id' => CdrModule::t('cdr', 'user_id'),
            'sp_id' => CdrModule::t('cdr', 'sp_id'),
            'user_name' => CdrModule::t('cdr', 'user_name'), //
            'sp_name' => CdrModule::t('cdr', 'sp_name'), //
            'outpluse_caller_id_number' => CdrModule::t('cdr', 'outpluse_caller_id_number'),
            'outpluse_dialed_number' => CdrModule::t('cdr', 'outpluse_dialed_number'),
            'free_min' => CdrModule::t('cdr', 'free_min'), //
            'billed_min' => CdrModule::t('cdr', 'billed_min'), //
            'sell_cost' => CdrModule::t('cdr', 'sell_cost'),
            'sell_rc_id' => CdrModule::t('cdr', 'sell_rc_id'),
            'sell_rc_name' => CdrModule::t('cdr', 'sell_rc_name'),//
            'sell_rate' => CdrModule::t('cdr', 'sell_rate'),
            'sell_min_duration' => CdrModule::t('cdr', 'sell_min_duration'),
            'buy_cost' => CdrModule::t('cdr', 'buy_cost'),
            'buy_rc_id' => CdrModule::t('cdr', 'buy_rc_id'),
            'buy_rc_name' => CdrModule::t('cdr', 'buy_rc_name'),//
            'buy_rate' => CdrModule::t('cdr', 'buy_rate'),
            'buy_min_duration' => CdrModule::t('cdr', 'buy_min_duration'),
            'service' => CdrModule::t('cdr', 'service'),
            'package_id' => CdrModule::t('cdr', 'package_id'),
            'package_name' => CdrModule::t('cdr', 'package_name'),//
            'call_type' => CdrModule::t('cdr', 'call_type'),
            'call_region' => CdrModule::t('cdr', 'call_region'),
            'start_epoch' => CdrModule::t('cdr', 'start_epoch'),
            'answer_epoch' => CdrModule::t('cdr', 'answer_epoch'),
            'end_epoch' => CdrModule::t('cdr', 'end_epoch'),
            'callstatus' => CdrModule::t('cdr', 'callstatus'),
            'duration' => CdrModule::t('cdr', 'duration'),
            'billsec' => CdrModule::t('cdr', 'billsec'),
            'trunk_id' => CdrModule::t('cdr', 'trunk_id'),
            'trunk_name' => CdrModule::t('cdr', 'trunk_name'),
            'forward_to' => CdrModule::t('cdr', 'forward_to'),
            'hangup' => CdrModule::t('cdr', 'hangup')
        ];
    }
}
