<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 11:10 AM
 */

namespace app\modules\ecosmob\reports\models;

use app\modules\ecosmob\reports\ReportsModule;

class ExtensionCallSummary extends \yii\mongodb\ActiveRecord {
    
    public static function collectionName () {
        return [ 'calltech', 'uctenant.cdr' ];
    }
    
    public static function allColumns () {
        return [
            'extension'              =>
                [
                    'attribute'      => 'extension',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'internal-call-count'    =>
                [
                    'attribute'      => 'internal-call-count',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'internal-call-duration' =>
                [
                    'attribute'      => 'internal-call-duration',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'external-call-count'    =>
                [
                    'attribute'      => 'external-call-count',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'external-call-duration' =>
                [
                    'attribute'      => 'external-call-duration',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'total-call-count'       =>
                [
                    'attribute'      => 'total-call-count',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
            'total-call-duration'    =>
                [
                    'attribute'      => 'total-call-duration',
                    'headerOptions'  => [ 'class' => 'text-center' ],
                    'contentOptions' => [ 'class' => 'text-center' ],
                ],
        ];
    }
    
    public function rules () {
        return [
            [
                [
                    '_id',
                    'uuid',
                    'extension',
                    'internal-call-count',
                    'internal-call-duration',
                    'external-call-count',
                    'external-call-duration',
                    'total-call-count',
                    'total-call-duration',
                    'start_epoch',
                    'end_epoch',
                    'extension',
                    'call_type',
                    'dialed_number',
                    'caller_id_number',
                    'direction',
                ],
                'safe',
            ],
        ];
    }
    
    public function attributes () {
        return [
            '_id',
            'uuid',
            'extension',
            'internal-call-count',
            'internal-call-duration',
            'external-call-count',
            'external-call-duration',
            'total-call-count',
            'total-call-duration',
            'start_epoch',
            'end_epoch',
            'extension',
            'call_type',
            'dialed_number',
            'caller_id_number',
            'direction',
        ];
    }
    
    public function attributeLabels () {
        return [
            'uuid'                   => ReportsModule::t( 'reports', 'uuid' ),
            'extension'              => ReportsModule::t( 'reports', 'extension' ),
            'internal-call-count'    => ReportsModule::t( 'reports', 'internal-call-count' ),
            'internal-call-duration' => ReportsModule::t( 'reports', 'internal-call-duration' ),
            'external-call-count'    => ReportsModule::t( 'reports', 'external-call-count' ),
            'external-call-duration' => ReportsModule::t( 'reports', 'external-call-duration' ),
            'total-call-count'       => ReportsModule::t( 'reports', 'total-call-count' ),
            'total-call-duration'    => ReportsModule::t( 'reports', 'total-call-duration' ),
            'dialed_number'          => ReportsModule::t( 'reports', 'dialed_number' ),
            'caller_id_number'       => ReportsModule::t( 'reports', 'caller_id_number' ),
            'call_type'              => ReportsModule::t( 'reports', 'call_type' ),
            'start_epoch'            => ReportsModule::t( 'reports', 'start_epoch' ),
            'end_epoch'              => ReportsModule::t( 'reports', 'end_epoch' ),
            'direction'              => ReportsModule::t( 'reports', 'direction' ),
        ];
    }
}
