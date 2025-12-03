<?php

namespace app\modules\ecosmob\cdr\models;

use app\components\CommonHelper;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\cdr\CdrModule;
use Yii;
use yii\helpers\Url;
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
            'uuid' =>
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'headerOptions' => ['class' => "check_boxes text-center"],
                    //'contentOptions' => ['class' => 'text-center check_boxes '],
                ],
            'call_id' =>
                [
                    'attribute' => 'call_id',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return (!empty($model->call_id) ? $model->call_id : '-');
                    },
                    'enableSorting' => True,
                ],
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
                        return ($model->dialed_number == NULL) ? "-" : (($model->dialed_number == "") ? "-" : str_replace("%2B", "+", $model->dialed_number));
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
                        return ($model->duration == NULL)
                            ?
                            "N/A"
                            : (($model->duration == "")
                                ? "N/A"
                                :
                                $model->duration);
                    },
                    'enableSorting' => True,
                ],
            'billsec' =>
                [
                    'attribute' => 'billsec',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->billsec == NULL)
                            ?
                            "N/A"
                            : (($model->billsec == "")
                                ? "N/A"
                                :
                                $model->billsec);
                    },
                    'enableSorting' => True,
                ],
            'trunk_name' =>
                [
                    'attribute' => 'trunk_name',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        if (!empty($model->trunk_name)) {
                            $trunkName = $model->trunk_name;
                            $position = strpos($model->trunk_name, '_');
                            if ($position !== false) {
                                $partBeforeUnderscore = substr($model->trunk_name, 0, $position);
                                if ($partBeforeUnderscore == $GLOBALS['tenantID'] || strtolower($partBeforeUnderscore) == 'sp') {
                                    $trunkName = substr($model->trunk_name, $position + 1);
                                }
                            }
                            $trunk = TrunkMaster::findOne(['trunk_name' => $trunkName]);
                            if(!empty($trunk)){
                                if($trunk->from_service == '1'){
                                    return $trunk->trunk_display_name;
                                }else{
                                    return $trunkName;
                                }
                            }else{
                                return '-';
                            }
                        }else{
                            return '-';
                        }
                    },
                    'enableSorting' => True,
                ],
            'hangup' =>
                [
                    'attribute' => 'hangup',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return ($model->hangup == NULL)
                            ? "N/A"
                            : (($model->hangup == "")
                                ? "N/A"
                                :
                                $model->hangup
                            );
                    },
                    'enableSorting' => True,
                ],
            'sip_response_code' =>
                [
                    'attribute' => 'sip_response_code',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return (!empty($model->sip_response_code) ? $model->sip_response_code : '-');
                    },
                    'enableSorting' => True,
                ],
            'service_type' =>
                [
                    'attribute' => 'service_type',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function ($model) {
                        return (!empty($model->service_type) ? $model->service_type : '-');
                    },
                    'enableSorting' => True,
                ],
            [
                'attribute' => 'uuid',
                'label' => CdrModule::t('cdr', 'action'),
                //'label'=>'Action',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center inline-class action_space'],
                'enableSorting' => True,
                'value' => function ($model) {
                    $record_filename = $model->record_filename;
                    if ($record_filename != '') {
                        $end = explode('/', $record_filename);
                        $end = array_reverse($end)[0];
                        $audioFilePath = Url::to('@web' . '/media/recordings');
                        $url = $audioFilePath .'/'.$GLOBALS['tenantID'].'/'.$end;
                        $basePath = Url::to('@webroot' . '/media/recordings/'.$GLOBALS['tenantID'].'/'.$end);
                        if(file_exists($basePath)) {
                            return '<audio controls="controls" controlsList="download">
                                                    <source src="' . $url . '" type="audio/wav">
                                                </audio>';
                        }else{
                            return '-';
                        }

                    } else {
                        return '-';
                    }
                },
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
                    "call_id",
                    "service_type",
                    "sip_response_code",
                    "original_caller_id"
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
            "call_id",
            "service_type",
            "sip_response_code",
            "original_caller_id"
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => CdrModule::t('cdr', 'uuid'),
            'sip_call_id' => CdrModule::t('cdr', 'sip_call_id'),
            'dialed_number' => CdrModule::t('cdr', 'dialed_number'),
            'caller_id_number' => CdrModule::t('cdr', 'caller_id_number'),
            'isfile' => CdrModule::t('cdr', 'isfile'),
            /*'user_id'                   => CdrModule::t( 'cdr', 'user_id' ),*/
            /*'sp_id'                     => CdrModule::t( 'cdr', 'sp_id' ),*/
            /*'user_name'                 => CdrModule::t( 'cdr', 'user_name' ),*/ //
            /*'sp_name'                   => CdrModule::t( 'cdr', 'sp_name' ),*/ //
            /*'outpluse_caller_id_number' => CdrModule::t( 'cdr', 'outpluse_caller_id_number' ),
            'outpluse_dialed_number'    => CdrModule::t( 'cdr', 'outpluse_dialed_number' ),
            'free_min'                  => CdrModule::t( 'cdr', 'free_min' ), //
            'billed_min'                => CdrModule::t( 'cdr', 'billed_min' ), //
            'sell_cost'                 => CdrModule::t( 'cdr', 'sell_cost' ),
            'sell_rc_id'                => CdrModule::t( 'cdr', 'sell_rc_id' ),
            'sell_rc_name'              => CdrModule::t( 'cdr', 'sell_rc_name' ),//
            'sell_rate'                 => CdrModule::t( 'cdr', 'sell_rate' ),
            'sell_min_duration'         => CdrModule::t( 'cdr', 'sell_min_duration' ),
            'buy_cost'                  => CdrModule::t( 'cdr', 'buy_cost' ),
            'buy_rc_id'                 => CdrModule::t( 'cdr', 'buy_rc_id' ),
            'buy_rc_name'               => CdrModule::t( 'cdr', 'buy_rc_name' ),//
            'buy_rate'                  => CdrModule::t( 'cdr', 'buy_rate' ),
            'buy_min_duration'          => CdrModule::t( 'cdr', 'buy_min_duration' ),
            'service'                   => CdrModule::t( 'cdr', 'service' ),
            'package_id'                => CdrModule::t( 'cdr', 'package_id' ),
            'package_name'              => CdrModule::t( 'cdr', 'package_name' ),//*/
            'call_type' => CdrModule::t('cdr', 'call_type'),
            /*'call_region'               => CdrModule::t( 'cdr', 'call_region' ),*/
            'start_epoch' => CdrModule::t('cdr', 'start_epoch'),
            'answer_epoch' => CdrModule::t('cdr', 'answer_epoch'),
            'end_epoch' => CdrModule::t('cdr', 'end_epoch'),
            'callstatus' => CdrModule::t('cdr', 'callstatus'),
            'direction' => CdrModule::t('cdr', 'direction'),
            'duration' => CdrModule::t('cdr', 'duration'),
            'billsec' => CdrModule::t('cdr', 'billsec'),
            'ext_call' => CdrModule::t('cdr', 'ext_call'),
            'trunk_id' => CdrModule::t('cdr', 'trunk_id'),
            'trunk_name' => CdrModule::t('cdr', 'trunk_name'),
            /*'forward_to'                => CdrModule::t( 'cdr', 'forward_to' ),*/
            'hangup' => CdrModule::t('cdr', 'hangup'),
            'call_id' => CdrModule::t('cdr', 'call_id'),
            "service_type" => CdrModule::t('cdr', 'service_type'),
        ];
    }
}
