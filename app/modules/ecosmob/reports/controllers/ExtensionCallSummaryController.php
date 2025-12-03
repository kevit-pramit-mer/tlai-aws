<?php

namespace app\modules\ecosmob\reports\controllers;

use app\modules\ecosmob\reports\models\ExtensionCallSummary;
use app\modules\ecosmob\reports\models\ExtensionCallSummarySearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class ExtensionCallSummaryController
 *
 * @package app\modules\ecosmob\reports\controllers
 */
class ExtensionCallSummaryController extends \yii\web\Controller {
    
    /**
     * @return array
     */
    public function behaviors () {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'export',
                        ],
                        'allow'   => TRUE,
                        'roles'   => [ '@' ],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }
    
    /**
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex () {
        $searchModel  = new ExtensionCallSummarySearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        
        Yii::$app->session->set( 'cdrquery', $dataProvider->query );
        
        
        return $this->render( 'index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ] );
    }
    
    /**
     *
     */
    public function actionExport () {
        $fp = fopen( 'php://temp', 'w' );
        
        $fileName = "Outbound_CDR_Report_" . time() . ".csv";
        
        $model = new ExtensionCallSummary();
        
        $query = Yii::$app->session->get( 'cdrquery' );
        
        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'pagination' => FALSE,
        ] );
        
        $records = $dataProvider->getModels();
        
        $attr = [
            "uuid",
            "sip_call_id",
            "dialed_number",
            "caller_id_number",
            "record_filename",
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
            "callstatus",
            "direction",
            "duration",
            "billsec",
            "trunk_id",
            "trunk_name",
            /*"forward_to",*/
            "hangup",
            "isfile",
        
        ];
        
        $row = [];
        foreach ( $attr as $header ) {
            $row[] = $model->getAttributeLabel( $header );
        }
        
        fputcsv( $fp, $row );
        
        if ( ! empty( $records ) ) {
            foreach ( $records as $record ) {
                $row = [];
                foreach ( $attr as $head ) {
                    $row[ $head ] = $record->$head;
                    if ( $head == "start_epoch" || $head == "answer_epoch" || $head == "end_epoch" ) {
                        if ( $record->$head != '0' ) {
                            $row[ $head ] = Yii::$app->helper->tsToDt( $record->$head );
                        } else {
                            $row[ $head ] = '';
                        }
                    }
                }
                fputcsv( $fp, $row );
            }
        }
        
        rewind( $fp );
        header( 'Content-type: application/csv' );
        header( 'Content-Disposition: attachment; filename=' . $fileName );
        $file = stream_get_contents( $fp );
        echo $file;
        fclose( $fp );
        exit;
    }
    
}