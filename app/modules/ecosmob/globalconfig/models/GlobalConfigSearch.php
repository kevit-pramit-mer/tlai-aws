<?php /** @noinspection PhpUndefinedFieldInspection */

namespace app\modules\ecosmob\globalconfig\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemConfSearch represents the model behind the search form about `app\modules\ecosmob\globalconfig\models\GlobalConfig`.
 */
class GlobalConfigSearch extends GlobalConfig
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gwc_key'], 'string', 'max' => 30],
            [['gwc_id', 'gwc_key', 'gwc_value', 'gwc_type', 'gwc_description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = GlobalConfig::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['gwc_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'gwc_key', $this->gwc_key])->andFilterWhere([
            'like',
            'gwc_value',
            $this->gwc_value,
        ])->andFilterWhere(['like', 'gwc_type', $this->gwc_type,])->andFilterWhere([
            'like',
            'gwc_description',
            $this->gwc_description,
        ]);

        return $dataProvider;
    }
}
