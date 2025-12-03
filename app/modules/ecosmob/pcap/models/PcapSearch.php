<?php

namespace app\modules\ecosmob\pcap\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PcapSearch represents the model behind the search form of `app\modules\ecosmob\pcap\models\Pcap`.
 */
class PcapSearch extends Pcap
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ct_id'], 'integer'],
            [['ct_name', 'filter', 'buffer_size', 'packets_limit', 'ct_start', 'ct_stop', 'ct_filename', 'ct_url'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $from = date('Y-m-d', strtotime('-7 days'));
        $to = date("Y-m-d", strtotime('+1 days'));
        $query = Pcap::find()/*->where(['BETWEEN','ct_start', $from, $to])*/
            ->orderBy(['ct_id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'ct_name', $this->ct_name]);
        return $dataProvider;
    }
}
