<?php

namespace app\modules\ecosmob\dbbackup\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\dbbackup\models\DbBackup;
use app\components\CommonHelper;

/**
 * DbBackupSearch represents the model behind the search form of `app\modules\ecosmob\dbbackup\models\DbBackup`.
 */
class DbBackupSearch extends DbBackup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['db_id'], 'integer'],
            [['db_name', 'db_path', 'db_date', 'from_db_date', 'to_db_date', 'db_created_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = DbBackup::find();

        $primaryKey = DbBackup::primaryKey()[0];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
	
	if($this->from_db_date == "" && $this->to_db_date == "")
	{
		$this->from_db_date = date('Y-m-d', strtotime('-25 day', strtotime(date('Y-m-d'))));
		$this->to_db_date   = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
	}

	$from_db_date = CommonHelper::DtTots($this->from_db_date . ' 00:00:01');
	$to_db_date = CommonHelper::DtTots($this->to_db_date . ' 23:59:59');
	$query->andFilterWhere(['>=', 'db_date', trim($from_db_date)]);
	$query->andFilterWhere(['<=', 'db_date', trim($to_db_date)]);

        // grid filtering conditions
        $query->andFilterWhere([
            'db_id' => $this->db_id,
            'db_created_date' => $this->db_created_date,
        ]);

        $query->andFilterWhere(['like', 'db_name', $this->db_name])
            ->andFilterWhere(['like', 'db_path', $this->db_path]);

        return $dataProvider;
    }
}

