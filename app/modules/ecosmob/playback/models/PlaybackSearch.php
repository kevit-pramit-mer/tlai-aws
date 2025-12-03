<?php

namespace app\modules\ecosmob\playback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\playback\models\Playback;

/**
 * PlaybackSearch represents the model behind the search form of `app\modules\ecosmob\playback\models\Playback`.
 */
class PlaybackSearch extends Playback {
    
    /**
     * {@inheritdoc}
     */
    public function rules () {
        return [
            [ [ 'pb_id' ], 'integer' ],
            [ [ 'pb_name', 'pb_language', 'pb_file' ], 'safe' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidArgumentException
     */
    public function search ( $params ) {
        $query = Playback::find();
        
        $primaryKey = Playback::primaryKey()[0];
        
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'sort'       => [ 'defaultOrder' => [ $primaryKey => SORT_DESC ] ],
            'pagination' => [ 'pageSize' => Yii::$app->layoutHelper->get_per_page_record_count() ],
        ] );
        
        $this->load( $params );
        
        if ( ! $this->validate() ) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere( [
            'pb_id'        => $this->pb_id,
        ] );
        
        $query->andFilterWhere( [ 'like', 'pb_name', $this->pb_name ] )
              ->andFilterWhere( [ 'like', 'pb_language', $this->pb_language ] )
              ->andFilterWhere( [ 'like', 'pb_file', $this->pb_file ] );
        
        return $dataProvider;
    }
}
