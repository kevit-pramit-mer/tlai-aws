<?php

namespace app\modules\ecosmob\auth\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii2mod\rbac\models\AuthItemModel;
/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * @param $id
     * @return array|ActiveRecord|null
     */
    public static function getUserDetail($id)
    {
        return static::find()->where(['item_name' => 'tenant_operator'])->andWhere(['user_id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItemModel::className(), ['name' => 'item_name']);
    }
}
