<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 4/9/18
 * Time: 12:05 PM
 */

namespace app\modules\ecosmob\reports\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\reports\models\ExtensionCallSummary;

class ExtensionCallSummarySearch extends ExtensionCallSummary {
    
    public function rules () {
        return [
            [
                [
                    'uuid',
                    'extension',
                    'internal-call-count',
                    'internal-call-duration',
                    'external-call-count',
                    'external-call-duration',
                    'total-call-count',
                    'total-call-duration',
                    "start_epoch",
                    "end_epoch",
                    "extension",
                    "call_type",
                    "dialed_number",
                    "caller_id_number",
                    "direction",
                ],
                'safe',
            ],
        ];
    }
    
    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * @param $params
     *
     * @return \yii\data\ActiveDataProvider
     * @throws \yii\base\InvalidArgumentException
     */
    public function search ( $params ) {
        $query = ExtensionCallSummary::find();
        
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'sort'       => [ 'defaultOrder' => [ 'uuid' => SORT_DESC ] ],
            'pagination' => [ 'pageSize' => Yii::$app->layoutHelper->get_per_page_record_count() ],
        ] );
        
        $this->load( $params );
        
        if ( ! $this->validate() ) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        
        if ( Yii::$app->session->get( 'loginAsExtension' ) ) {
            $query->andFilterWhere( [ 'caller_id_number' => Yii::$app->user->identity->em_extension_number ] );
        } else {
            $query->andFilterWhere( [ 'like', "caller_id_number", $this->caller_id_number ] );
            
        }
        
        
        if ( $this->start_epoch ) {
            $start_time = strtotime( $this->start_epoch . '00:00:01' );//            2019-09-28 00:00:01
            $end_time   = strtotime( $this->start_epoch . '23:59:59' );//            2019-09-28 23:59:59
            $query->andFilterWhere( [ '>', 'start_epoch', trim( $start_time ) ] );
            $query->andFilterWhere( [ '<', 'start_epoch', trim( $end_time ) ] );
        }
        
        if ( $this->end_epoch ) {
            $start_time = strtotime( $this->end_epoch . ' 00:00:01' );//            2019-09-28 00:00:01
            $end_time   = strtotime( $this->end_epoch . ' 23:59:59' );//            2019-09-28 23:59:59
            $query->andFilterWhere( [ '>=', 'end_epoch', trim( $start_time ) ] );
            $query->andFilterWhere( [ '<=', 'end_epoch', trim( $end_time ) ] );
        }
        
        $query->andFilterWhere( [ 'like', 'uuid', $this->uuid ] )
              ->andFilterWhere( [ 'like', "dialed_number", $this->dialed_number ] )
              ->andFilterWhere( [ 'like', "call_type", $this->call_type ] );
        
        //   ->andFilterWhere(['like', 'fcd_notify_admin', $this->fcd_notify_admin]
        //);
        
        /*foreach (['start_epoch', 'answer_epoch', 'end_epoch'] as $epoch) {
            $date=explode(' - ', $this->{$epoch});

            if (isset($date[0]) && isset($date[1]) &&
                Yii::$app->helper->validateDate($date[0], 'Y-m-d H:i:s') && Yii::$app->helper->validateDate($date[1],
                    'Y-m-d H:i:s')
            ) {
                $query->andFilterWhere(['>=', $epoch, (string)Yii::$app->helper->dtToTs($date[0], 'Y-m-d H:i:s')]);
                $query->andFilterWhere(['<=', $epoch, (string)Yii::$app->helper->dtToTs($date[1], 'Y-m-d H:i:s')]);
                unset($date);
            }
        }*/
        
        return $dataProvider;
    }
}
