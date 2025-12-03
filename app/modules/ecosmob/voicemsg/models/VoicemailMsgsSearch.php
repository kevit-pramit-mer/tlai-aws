<?php

namespace app\modules\ecosmob\voicemsg\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VoicemailMsgsSearch represents the model behind the search form of `app\modules\ecosmob\voicemsg\models\VoicemailMsgs`.
 */
class VoicemailMsgsSearch extends VoicemailMsgs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_epoch', 'read_epoch', 'trash_epoch', 'sip_id', 'message_len'], 'integer'],
            [['username', 'domain', 'uuid', 'cid_name', 'cid_number', 'in_folder', 'file_path', 'flags', 'read_flags', 'forwarded_by'], 'safe'],
            ['status', 'safe']
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
        $query = VoicemailMsgs::find()->where(['domain' => $_SERVER['HTTP_HOST']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_epoch' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->load($params);

        if (isset($this->read_epoch)) {
            if ($this->read_epoch == 0) {
                $query->andFilterWhere(['read_epoch' => 0]);
            } else if ($this->read_epoch == 1) {
                $query->andFilterWhere(['>', 'read_epoch', 1]);
            } else {
                $query->andFilterWhere(['>=', 'read_epoch', 0]);
            }
        }

        $query->andFilterWhere([
            'created_epoch' => $this->created_epoch,
            'trash_epoch' => $this->trash_epoch,
            'sip_id' => $this->sip_id,
            'message_len' => $this->message_len,
            'status' => $this->status,
        ]);

        if (Yii::$app->session->get('loginAsExtension')) {
            $query->andFilterWhere(['=', 'username', trim(Yii::$app->user->identity->em_extension_number)]);
        }
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'cid_name', $this->cid_name])
            ->andFilterWhere(['like', 'cid_number', $this->cid_number])
            ->andFilterWhere(['like', 'in_folder', $this->in_folder])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'flags', $this->flags])
            ->andFilterWhere(['like', 'read_flags', $this->read_flags])
            ->andFilterWhere(['like', 'forwarded_by', $this->forwarded_by]);

        return $dataProvider;
    }
}
