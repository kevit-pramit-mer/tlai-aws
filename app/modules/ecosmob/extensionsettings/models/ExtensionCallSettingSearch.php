<?php

namespace app\modules\ecosmob\extensionsettings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting;

/**
 * ExtensionCallSettingSearch represents the model behind the search form of `app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting`.
 */
class ExtensionCallSettingSearch extends ExtensionCallSetting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ecs_id', 'em_id', 'ecs_max_calls', 'ecs_ring_timeout', 'ecs_call_timeout', 'ecs_ob_max_timeout'], 'integer'],
            [['ecs_auto_recording', 'ecs_dtmf_type', 'ecs_video_calling', 'ecs_bypass_media', 'ecs_srtp', 'ecs_force_record', 'ecs_moh', 'ecs_audio_codecs', 'ecs_video_codecs', 'ecs_dial_out', 'ecs_forwarding', 'ecs_voicemail', 'ecs_voicemail_password', 'ecs_fax2mail', 'ecs_feature_code_pin', 'ecs_multiple_registeration'], 'safe'],
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
        $query = ExtensionCallSetting::find();

        $primaryKey = ExtensionCallSetting::primaryKey()[0];

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

        // grid filtering conditions
        $query->andFilterWhere([
            'ecs_id' => $this->ecs_id,
            'em_id' => $this->em_id,
            'ecs_max_calls' => $this->ecs_max_calls,
            'ecs_ring_timeout' => $this->ecs_ring_timeout,
            'ecs_call_timeout' => $this->ecs_call_timeout,
            'ecs_ob_max_timeout' => $this->ecs_ob_max_timeout,
        ]);

        $query->andFilterWhere(['like', 'ecs_auto_recording', $this->ecs_auto_recording])
            ->andFilterWhere(['like', 'ecs_dtmf_type', $this->ecs_dtmf_type])
            ->andFilterWhere(['like', 'ecs_video_calling', $this->ecs_video_calling])
            ->andFilterWhere(['like', 'ecs_bypass_media', $this->ecs_bypass_media])
            ->andFilterWhere(['like', 'ecs_srtp', $this->ecs_srtp])
            ->andFilterWhere(['like', 'ecs_force_record', $this->ecs_force_record])
            ->andFilterWhere(['like', 'ecs_moh', $this->ecs_moh])
            ->andFilterWhere(['like', 'ecs_audio_codecs', $this->ecs_audio_codecs])
            ->andFilterWhere(['like', 'ecs_video_codecs', $this->ecs_video_codecs])
            ->andFilterWhere(['like', 'ecs_dial_out', $this->ecs_dial_out])
            ->andFilterWhere(['like', 'ecs_forwarding', $this->ecs_forwarding])
            ->andFilterWhere(['like', 'ecs_voicemail', $this->ecs_voicemail])
            ->andFilterWhere(['like', 'ecs_voicemail_password', $this->ecs_voicemail_password])
            ->andFilterWhere(['like', 'ecs_fax2mail', $this->ecs_fax2mail])
            ->andFilterWhere(['like', 'ecs_feature_code_pin', $this->ecs_feature_code_pin])
            ->andFilterWhere(['like', 'ecs_multiple_registeration', $this->ecs_multiple_registeration]);

        return $dataProvider;
    }
}
