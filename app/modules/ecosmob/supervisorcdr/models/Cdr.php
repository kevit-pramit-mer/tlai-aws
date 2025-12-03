<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 11:10 AM
 */

namespace app\modules\ecosmob\supervisorcdr\models;

use app\modules\ecosmob\customer\models\Customer;
use app\modules\ecosmob\serviceprovider\models\ServiceProvider;
use Yii;
use app\modules\ecosmob\supervisorcdr\SupervisorCdrModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

class Cdr extends \yii\mongodb\ActiveRecord
{

    public $isfile;

    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'uctenant.cdr'];
    }

    public static function allColumns()
    {
        return [
            /*'uuid'                      =>
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'headerOptions' => ['class' => "check_boxes text-center"],
                    //'contentOptions' => ['class' => 'text-center check_boxes '],
                ],*/
            /*[
                'attribute'      => 'uuid',
                'label'          => 'Action',
                'format'         => 'raw',
                'headerOptions'  => [ 'class' => 'text-center' ],
                'contentOptions' => [ 'class' => 'text-center inline-class action_space' ],
                'enableSorting'  => FALSE,
                'value'          => function ( $model ) {
                    $record_filename = $model->record_filename;
                    if($record_filename !=''){
                    $end = explode('/', $record_filename);
                    $end = array_reverse($end)[0];
                    $audioFilePath = Url::to( '@web' . '/media/recordings/' );
//                        $audioFilePath = Url::to(Yii::$app->params['adminStorageFullPath'] . 'recordings/');
                    $url = $audioFilePath.$end;

                    return '<audio controls="controls" controlsList="nodownload">
                            <source src="' . $url . '" type="audio/wav">
                        </audio>
                        <a href="' . $url . '" download="' . $url . '"><i class="material-icons" style="color: #474747">file_download</i></a>
                        ';

                     } else {
                            return '-';
                        }*/

            /* return ( ($record_filename != '') ? Html::a( '<i class="material-icons">play_arrow</i>',
                 $url,
                 [
                     'data-toggle'    => 'popover',
                     'data-placement' => 'top',
                     'data-trigger'   => "hover",
                     'data-content'   => 'Call Path',
                     'data-pjax'      => 0,
                     'class'          => 'btn btn-danger btn-sm',
                     'target'         => '_blank',
                 ] ) : '-' );*/


            /*},
        ],*/
            'dialed_number'=>
                [
                    'attribute'=>'dialed_number',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                ],
            'call_type'=>
                [
                    'attribute'=>'call_type',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                    'value'=>function ($model) {
                        return isset($model->call_type) ?
                            $model->call_type : "N/A";
                    },
                ],
            'trunk_name'=>
                [
                    'attribute'=>'trunk_name',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                    'value'=>function ($model) {
                        return ($model->trunk_name == NULL)
                            ?
                            "N/A"
                            : ($model->trunk_name == "")
                                ? "N/A"
                                :
                                $model->trunk_name;
                    },
                ],
            /*          'outpluse_dialed_number'    =>
                        [
                            'attribute'      => 'outpluse_dialed_number',
                            'headerOptions'  => [ 'class' => 'text-center' ],
                            'contentOptions' => [ 'class' => 'text-center' ],
                        ],
                    'outpluse_caller_id_number' =>
                        [
                            'attribute'      => 'outpluse_caller_id_number',
                            'headerOptions'  => [ 'class' => 'text-center' ],
                            'contentOptions' => [ 'class' => 'text-center' ],
                        ],*/
            /*'dialed_number' =>
                [
                    'attribute' => 'dialed_number',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/
            /*'user_name'                 =>
                [
                    'attribute'      => 'user_name',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],*/
            /* 'sp_name'                   =>
                 [
                     'attribute'      => 'sp_name',
                     'headerOptions'  => [ 'class' => 'text-center' ],
                     'contentOptions' => [ 'class' => 'text-center' ],
                 ],
             */
            'direction'=>
                [
                    'attribute'=>'direction',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                ],

            'callstatus'=>
                [
                    'attribute'=>'callstatus',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                ],
            'start_epoch'=>
                [
                    'attribute'=>'start_epoch',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                    'value'=>function ($model) {

                        $utc=$model->start_epoch;
                        $time=date("Y-m-d H:i:s", substr($utc, 0, 10));

                        return ($time == "0") ?
                            "N/A" : $time;
//                            "N/A" : Yii::$app->helper->tsToDt( $model->start_epoch );
                    },
                ],
            'end_epoch'=>
                [
                    'attribute'=>'end_epoch',
                    'headerOptions'=>['class'=>'text-center'],
                    'contentOptions'=>['class'=>'text-center'],
                    'value'=>function ($model) {
                        $utc=$model->end_epoch;
                        $time=date("Y-m-d H:i:s", substr($utc, 0, 10));

                        return ($time == "0") ?
                            "N/A" : $time;
                    },
                ],
            /* 'call_type'         =>
                 [
                     'attribute'      => 'call_type',
                     'headerOptions'  => [ 'class' => 'text-center' ],
                     'contentOptions' => [ 'class' => 'text-center' ],
                     'value'          => function ( $model ) {
                         return isset( $model->call_type ) ?
                             $model->call_type : "N/A";
                     },
                 ],
             'service'           =>
                 [
                     'attribute'      => 'service',
                     'headerOptions'  => [ 'class' => 'text-center' ],
                     'contentOptions' => [ 'class' => 'text-center' ],
                     'value'          => function ( $model ) {
                         return isset( $model->service ) ?
                             $model->service : "N/A";
                     },
                 ],
             'package_name'      =>
                 [
                     'attribute'      => 'package_name',
                     'headerOptions'  => [ 'class' => 'text-center' ],
                     'contentOptions' => [ 'class' => 'text-center' ],
                     'value'          => function ( $model ) {
                         return isset( $model->package_name ) ?
                             $model->package_name : "N/A";
                     },
                 ],*/
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    /*"sip_call_id",*/
                    "dialed_number",
                    "caller_id_number",
                    /*"record_filename",*/
                    /*"user_id",*/
                    /*"sp_id",*/
                    /*"user_name",*/ //
                    /*"sp_name",*/ //
                    /*"outpluse_caller_id_number",
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
                    "package_name", //*/
                    "call_type",
                    /*"call_region",*/
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                  /*  "callstatus",
                    "direction",
                    "duration",
                    "billsec",
                    "trunk_id",*/
                    "trunk_name",
                    /*"forward_to",*/
                    /*"hangup",*/
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
            /*"user_id",*/
            /*"sp_id",*/
            /*"user_name",*/ //done
            /*"sp_name",*/ //done
            /*          "outpluse_caller_id_number",
                      "outpluse_dialed_number",
                      "free_min", //done
                      "billed_min", //done
                      "sell_cost",
                      "sell_rc_id",
                      "sell_rc_name",//done
                      "sell_rate",
                      "sell_min_duration",
                      "buy_cost",
                      "buy_rc_id",
                      "buy_rc_name", //done
                      "buy_rate",
                      "buy_min_duration",
                      "service",
                      "package_id",
                      "package_name", //done*/
            "call_type",
            /*"call_region",*/
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
            /*"forward_to",*/
            "hangup",
            "isfile",
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid'=>SupervisorCdrModule::t('cdr', 'uuid'),
            'sip_call_id'=>SupervisorCdrModule::t('cdr', 'sip_call_id'),
            'dialed_number'=>SupervisorCdrModule::t('cdr', 'dialed_number'),
            'caller_id_number'=>SupervisorCdrModule::t('cdr', 'caller_id_number'),
            'isfile'=>SupervisorCdrModule::t('cdr', 'isfile'),
            /*'user_id'                   => SupervisorCdrModule::t( 'cdr', 'user_id' ),*/
            /*'sp_id'                     => SupervisorCdrModule::t( 'cdr', 'sp_id' ),*/
            /*'user_name'                 => SupervisorCdrModule::t( 'cdr', 'user_name' ),*/ //
            /*'sp_name'                   => SupervisorCdrModule::t( 'cdr', 'sp_name' ),*/ //
            /*'outpluse_caller_id_number' => SupervisorCdrModule::t( 'cdr', 'outpluse_caller_id_number' ),
            'outpluse_dialed_number'    => SupervisorCdrModule::t( 'cdr', 'outpluse_dialed_number' ),
            'free_min'                  => SupervisorCdrModule::t( 'cdr', 'free_min' ), //
            'billed_min'                => SupervisorCdrModule::t( 'cdr', 'billed_min' ), //
            'sell_cost'                 => SupervisorCdrModule::t( 'cdr', 'sell_cost' ),
            'sell_rc_id'                => SupervisorCdrModule::t( 'cdr', 'sell_rc_id' ),
            'sell_rc_name'              => SupervisorCdrModule::t( 'cdr', 'sell_rc_name' ),//
            'sell_rate'                 => SupervisorCdrModule::t( 'cdr', 'sell_rate' ),
            'sell_min_duration'         => SupervisorCdrModule::t( 'cdr', 'sell_min_duration' ),
            'buy_cost'                  => SupervisorCdrModule::t( 'cdr', 'buy_cost' ),
            'buy_rc_id'                 => SupervisorCdrModule::t( 'cdr', 'buy_rc_id' ),
            'buy_rc_name'               => SupervisorCdrModule::t( 'cdr', 'buy_rc_name' ),//
            'buy_rate'                  => SupervisorCdrModule::t( 'cdr', 'buy_rate' ),
            'buy_min_duration'          => SupervisorCdrModule::t( 'cdr', 'buy_min_duration' ),
            'service'                   => SupervisorCdrModule::t( 'cdr', 'service' ),
            'package_id'                => SupervisorCdrModule::t( 'cdr', 'package_id' ),
            'package_name'              => SupervisorCdrModule::t( 'cdr', 'package_name' ),//*/
            'call_type'=>SupervisorCdrModule::t('cdr', 'call_type'),
            /*'call_region'               => SupervisorCdrModule::t( 'cdr', 'call_region' ),*/
            'start_epoch'=>SupervisorCdrModule::t('cdr', 'start_epoch'),
            'answer_epoch'=>SupervisorCdrModule::t('cdr', 'answer_epoch'),
            'end_epoch'=>SupervisorCdrModule::t('cdr', 'end_epoch'),
            'callstatus'=>SupervisorCdrModule::t('cdr', 'callstatus'),
            'direction'=>SupervisorCdrModule::t('cdr', 'direction'),
            'duration'=>SupervisorCdrModule::t('cdr', 'duration'),
            'billsec'=>SupervisorCdrModule::t('cdr', 'billsec'),
            'ext_call'=>SupervisorCdrModule::t('cdr', 'ext_call'),
            'trunk_id'=>SupervisorCdrModule::t('cdr', 'trunk_id'),
            'trunk_name'=>SupervisorCdrModule::t('cdr', 'trunk_name'),
            /*'forward_to'                => SupervisorCdrModule::t( 'cdr', 'forward_to' ),*/
            'hangup'=>SupervisorCdrModule::t('cdr', 'hangup'),
        ];
    }
}
