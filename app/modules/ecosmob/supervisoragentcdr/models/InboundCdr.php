<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 29/9/18
 * Time: 11:10 AM
 */

namespace app\modules\ecosmob\supervisoragentcdr\models;

use Yii;
use app\modules\ecosmob\supervisoragentcdr\SupervisorAgentCdrModule;

class InboundCdr extends \yii\mongodb\ActiveRecord
{
    public static function collectionName()
    {
        return ['giptechdb', 'inbound.cdr'];
    }

    public static function allColumns()
    {
        return [
            /*'main_uuid' =>
                [
                    'attribute' => 'main_uuid',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/
            'outpluse_dialed_number' =>
                [
                    'attribute' => 'outpluse_dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->outpluse_dialed_number == null) ?
                            "N/A" : ($model->outpluse_dialed_number == "") ? "N/A" :
                                $model->outpluse_dialed_number;
                    }
                ],
            'outpluse_caller_id_number' =>
                [
                    'attribute' => 'outpluse_caller_id_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->outpluse_caller_id_number == null) ?
                            "N/A" : ($model->outpluse_caller_id_number == "") ? "N/A" :
                                $model->outpluse_caller_id_number;
                    }
                ],
            'user_name' =>
                [
                    'attribute' => 'user_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center']
                ],
            'sp_name' =>
                [
                    'attribute' => 'sp_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center']
                ],
            'direction' =>
                [
                    'attribute' => 'direction',
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
            'duration' =>
                [
                    'attribute' => 'duration',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
            'billsec' =>
                [
                    'attribute' => 'billsec',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
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
            'cost' =>
                [
                    'attribute' => 'cost',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->cost == null) ?
                            "N/A" : ($model->cost == "") ? "N/A" :
                                $model->cost;
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
            /* 'forward_by' =>
                 [
                     'attribute' => 'forward_by',
                     'headerOptions' => ['class' => 'text-center'],
                     'contentOptions' => ['class' => 'text-center'],
                     'value' => function ($model) {
                         return ($model->forward_by == null) ?
                             "N/A" : ($model->forward_by == "") ? "N/A" :
                                 $model->forward_by;
                     }
                 ],*/
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
                    "_id",
                    "uuid",
                    "main_uuid",
                    "dialed_number",
                    "caller_id_number",
                    "user_id",
                    "sp_id",
                    "user_name",
                    "sp_name",
                    "outpluse_caller_id_number",
                    "outpluse_dialed_number",
                    "did_type",
                    "did_id",
                    "flat_rate",
                    "free_min",
                    "billed_min",
                    "cost",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "trunk_id",
                    "trunk_name",
                    "direction",
                    "duration",
                    "billsec",
                    "forward_by",
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
            "main_uuid",
            "dialed_number",
            "caller_id_number",
            "user_id",
            "sp_id",
            "user_name",
            "sp_name",
            "outpluse_caller_id_number",
            "outpluse_dialed_number",
            "did_type",
            "did_id",
            "flat_rate",
            "free_min",
            "billed_min",
            "cost",
            "call_type",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "callstatus",
            "trunk_id",
            "trunk_name",
            "direction",
            "duration",
            "billsec",
            "forward_by",
            "hangup"
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => CdrModule::t('cdr', 'uuid'),
            'main_uuid' => CdrModule::t('cdr', 'main_uuid'),
            'dialed_number' => CdrModule::t('cdr', 'dialed_number'),
            'caller_id_number' => CdrModule::t('cdr', 'caller_id_number'),
            'user_id' => CdrModule::t('cdr', 'user_id'),
            'sp_id' => CdrModule::t('cdr', 'sp_id'),
            'user_name' => CdrModule::t('cdr', 'user_name'),
            'sp_name' => CdrModule::t('cdr', 'sp_name'),
            'outpluse_caller_id_number' => CdrModule::t('cdr', 'outpluse'),
            'outpluse_dialed_number' => CdrModule::t('cdr', 'outpluse_dialed_number'),
            'did_type' => CdrModule::t('cdr', 'did_type'),
            'did_id' => CdrModule::t('cdr', 'did_id'),
            'free_min' => CdrModule::t('cdr', 'free_min'),
            'billed_min' => CdrModule::t('cdr', 'billed_min'),
            'cost' => CdrModule::t('cdr', 'cost'),
            'call_type' => CdrModule::t('cdr', 'call_type'),
            'start_epoch' => CdrModule::t('cdr', 'start_epoch'),
            'answer_epoch' => CdrModule::t('cdr', 'answer_epoch'),
            'end_epoch' => CdrModule::t('cdr', 'end_epoch'),
            'callstatus' => CdrModule::t('cdr', 'callstatus'),
            'trunk_id' => CdrModule::t('cdr', 'trunk_id'),
            'trunk_name' => CdrModule::t('cdr', 'trunk_name'),
            'direction' => CdrModule::t('cdr', 'direction'),
            'duration' => CdrModule::t('cdr', 'duration'),
            'billsec' => CdrModule::t('cdr', 'billsec'),
            'forward_by' => CdrModule::t('cdr', 'forward_by'),
            'hangup' => CdrModule::t('cdr', 'hangup')
        ];
    }
}
