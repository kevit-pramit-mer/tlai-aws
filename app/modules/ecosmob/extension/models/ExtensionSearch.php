<?php

namespace app\modules\ecosmob\extension\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ExtensionSearch represents the model behind the search form of `app\modules\ecosmob\extension\models\Extension`.
 */
class ExtensionSearch extends Extension
{
    /*
     * {@inheritdoc}
     */
    public $s_id;

    public function rules()
    {
        return [
            [['em_id', 'em_plan_id', 's_id', 'em_group_id', 'em_language_id', 'em_timezone_id', 'is_phonebook'], 'integer'],
            [['em_extension_name', 'em_password', 'em_web_password', 'em_extension_number', 'em_status', 'em_email'], 'safe'],
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
        $query = Extension::find()->joinWith(['callsettings', 'group', 'timezone', 'shift']);
//        $query = Extension::find()->select('*')->joinWith('callsettings')->joinWith('siftlist')->joinWith('group');
//        $query = Extension::find()->select('*')->joinWith('callsettings')->joinWith('siftlist')->joinWith('group');

        $primaryKey = Extension::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $dataProvider->sort->attributes['ecs_multiple_registeration'] = [
            'asc' => ['ecs_multiple_registeration' => SORT_ASC],
            'desc' => ['ecs_multiple_registeration' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['ecs_fax2mail'] = [
            'asc' => ['ecs_fax2mail' => SORT_ASC],
            'desc' => ['ecs_fax2mail' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'em_id' => $this->em_id,
            'em_plan_id' => $this->em_plan_id,
            'em_shift_id' => $this->s_id,
            'em_group_id' => $this->em_group_id,
            'em_language_id' => $this->em_language_id,
            'em_timezone_id' => $this->em_timezone_id,
            'is_phonebook' => $this->is_phonebook,
        ]);

        $query->andFilterWhere(['like', 'em_extension_name', $this->em_extension_name])
            ->andFilterWhere(['like', 'em_password', $this->em_password])
            ->andFilterWhere(['like', 'em_web_password', $this->em_web_password])
            ->andFilterWhere(['like', 'em_extension_number', $this->em_extension_number])
            ->andFilterWhere(['like', 'em_status', $this->em_status])
            ->andFilterWhere(['like', 'em_email', $this->em_email]);

        return $dataProvider;
    }
}
