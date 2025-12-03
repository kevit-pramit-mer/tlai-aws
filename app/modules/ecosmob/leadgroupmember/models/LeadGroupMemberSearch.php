<?php

namespace app\modules\ecosmob\leadgroupmember\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LeadGroupMemberSearch represents the model behind the search form of `app\modules\ecosmob\leadgroupmember\models\LeadGroupMember`.
 */
class LeadGroupMemberSearch extends LeadGroupMember
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lg_id'], 'integer'],
            [['lg_first_name', 'lg_last_name', 'lg_contact_number', 'lg_email_id', 'lg_address', 'lg_alternate_number', 'lg_pin_code', 'lg_permanent_address'], 'safe'],
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
        $query = LeadGroupMember::find();

        $primaryKey = LeadGroupMember::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'lg_id' => $this->lg_id,
            'ld_id' => $this->ld_id,
        ]);

        $query->andFilterWhere(['like', 'lg_first_name', $this->lg_first_name])
            ->andFilterWhere(['like', 'lg_last_name', $this->lg_last_name])
            ->andFilterWhere(['like', 'lg_contact_number', $this->lg_contact_number])
            ->andFilterWhere(['like', 'lg_email_id', $this->lg_email_id])
            ->andFilterWhere(['like', 'lg_address', $this->lg_address])
            ->andFilterWhere(['like', 'lg_alternate_number', $this->lg_alternate_number])
            ->andFilterWhere(['like', 'lg_pin_code', $this->lg_pin_code])
            ->andFilterWhere(['like', 'lg_permanent_address', $this->lg_permanent_address]);

        return $dataProvider;
    }
}
