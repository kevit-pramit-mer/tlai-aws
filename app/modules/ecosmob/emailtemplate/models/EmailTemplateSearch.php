<?php

namespace app\modules\ecosmob\emailtemplate\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\emailtemplate\models\EmailTemplate;

/**
 * EmailTemplateSearch represents the model behind the search form of `app\modules\ecosmob\emailtemplate\models\EmailTemplate`.
 */
class EmailTemplateSearch extends EmailTemplate {
    
    /**
     * {@inheritdoc}
     */
    public function rules () {
        return [
            [ [ 'id' ], 'integer' ],
            [ [ 'key', 'subject', 'content', 'created_at', 'updated_at' ], 'safe' ],
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
     */
    public function search ( $params ) {
        $query = EmailTemplate::find();
        
        $primaryKey = EmailTemplate::primaryKey()[0];
        
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
            'id'         => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ] );
        
        $query->andFilterWhere( [ 'like', 'key', $this->key ] )
              ->andFilterWhere( [ 'like', 'subject', $this->subject ] )
              ->andFilterWhere( [ 'like', 'content', $this->content ] );
        
        return $dataProvider;
    }
}
