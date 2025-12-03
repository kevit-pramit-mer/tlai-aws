<?php

namespace app\modules\ecosmob\license\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "license_ticket_management".
 *
 * @property int $id
 * @property string $ticket_unique_id
 * @property string $allocated
 * @property string $requested
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class LicenseTicketManagement extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'license_ticket_management';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_unique_id', 'created_at', 'updated_at'], 'safe'],
            ['ticket_unique_id', 'unique'],
            [['allocated', 'requested'], 'string'],
            [['status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_unique_id' => 'Ticket ID',
            'allocated' => 'Allocated',
            'requested' => 'Requested',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function search($params)
    {
        $query = LicenseTicketManagement::find();

        $primaryKey = LicenseTicketManagement::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => 5],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
