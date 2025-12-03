<?php /** @noinspection PhpUndefinedFieldInspection */

namespace app\modules\ecosmob\carriertrunk\models;

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NtcTrunkGroupSearch represents the model behind the search form about `app\modules\ecosmob\carriertrunk\models\NtcTrunkGroup`.
 */
class TrunkGroupSearch extends TrunkGroup
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trunk_grp_id'], 'integer'],
            [['trunk_grp_name'], 'string', 'max' => 30],
            [['trunk_grp_desc'], 'string', 'max' => 255],
            [
                [
                    'trunk_grp_name',
                    'trunk_grp_desc',
                    'trunk_grp_status',
                ],
                'safe',
            ],
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
        $query = TrunkGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['trunk_grp_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->where(['<>', 'trunk_grp_status', 'X']);
        $query->andFilterWhere([
            'trunk_grp_id' => $this->trunk_grp_id,
        ]);

        $query->andFilterWhere(['like', 'trunk_grp_name', $this->trunk_grp_name])->andFilterWhere([
            'like',
            'trunk_grp_desc',
            $this->trunk_grp_desc,
        ])->andFilterWhere([
            'like',
            'trunk_grp_status',
            $this->trunk_grp_status,
        ]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trunk_grp_name' => CarriertrunkModule::t('carriertrunk', 'name'),
            'trunk_grp_status' => CarriertrunkModule::t(
                'carriertrunk',
                'status'
            ),

        ];
    }
}
