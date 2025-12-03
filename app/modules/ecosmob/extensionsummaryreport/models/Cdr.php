<?php

namespace app\modules\ecosmob\extensionsummaryreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;


$extensionLists = Extension::find()->all();
foreach ($extensionLists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}
$ext = ArrayHelper::map($extensionLists, 'em_extension_number', 'em_extension_name');

class Cdr extends ActiveRecord
{
    public $internal_call, $extension, $extension_name, $total_calls, $total_duration, $average_call_duration,
            $total_answered_calls, $total_abandoned_calls, $total_inbound_calls, $total_inbound_call_duration,
            $total_outbound_calls, $total_outbound_call_duration;

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
                    'value' => function ($model) {
                        return ($model->caller_id_number == NULL ? "-" : ($model->caller_id_number == "" ? "-" : $model->caller_id_number));
                    },
                ],

            'dialed_number' =>
                [
                    'attribute' => 'dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'enableSorting' => True,
                    'value' => function ($model) {
                        return ($model->dialed_number == NULL ? "-" : ($model->dialed_number == "" ? "-" : str_replace("%2B", "+", $model->dialed_number)));
                    },
                ],
            'direction' =>
                [
                    'attribute' => 'direction',
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
                        return ($model->duration == NULL ? "N/A" : ($model->duration == "" ? "N/A" : $model->duration));
                    },
                    'enableSorting' => True,
                ],
            'hangup' =>
                [
                    'attribute' => 'hangup',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->hangup == null ? "N/A" : ($model->hangup == "" ? "N/A" : (gettype($model->hangup) == 'string' ? $model->hangup : "-")));
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
                    "dialed_number",
                    "caller_id_number",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "direction",
                    "duration",
                    "hangup",
                    "callstatus",
                    "call_id",
                    "service_type",
                    "extension",
                    "extension_name",
                    "total_calls",
                    "total_duration",
                    "average_call_duration",
                    "total_answered_calls",
                    "total_abandoned_calls",
                    "total_inbound_calls",
                    "total_inbound_call_duration",
                    "total_outbound_calls",
                    "total_outbound_call_duration"
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
            "direction",
            "duration",
            "hangup",
            "callstatus",
            "call_id",
            "service_type",
            "extension",
            "extension_name",
            "total_calls",
            "total_duration",
            "average_call_duration",
            "total_answered_calls",
            "total_abandoned_calls",
            "total_inbound_calls",
            "total_inbound_call_duration",
            "total_outbound_calls",
            "total_outbound_call_duration"
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'uuid'),
            'sip_call_id' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'sip_call_id'),
            'dialed_number' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'dialed_number'),
            'caller_id_number' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'caller_id_number'),
            'isfile' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'isfile'),
            'call_type' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'call_type'),
            'start_epoch' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'start_epoch'),
            'answer_epoch' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'answer_epoch'),
            'end_epoch' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'end_epoch'),
            'callstatus' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'callstatus'),
            'direction' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'direction'),
            'duration' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'duration'),
            'billsec' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'billsec'),
            'ext_call' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'ext_call'),
            'trunk_id' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'trunk_id'),
            'trunk_name' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'trunk_name'),
            'hangup' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'hangup'),
            'extension' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension'),
            'extension_name' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension_name'),
            'total_calls' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_calls'),
            'total_duration' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_duration'),
            'average_call_duration' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'average_call_duration'),
            'total_answered_calls' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_answered_calls'),
            'total_abandoned_calls' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_abandoned_calls'),
            'total_inbound_calls' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_inbound_calls'),
            'total_inbound_call_duration' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_inbound_call_duration'),
            'total_outbound_calls' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_outbound_calls'),
            'total_outbound_call_duration' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_outbound_call_duration'),
        ];
    }
}
