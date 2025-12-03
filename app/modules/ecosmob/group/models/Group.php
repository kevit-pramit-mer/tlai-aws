<?php

namespace app\modules\ecosmob\group\models;

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\group\GroupModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_group".
 *
 * @property int $grp_id
 * @property string $grp_name
 * @property string $grp_description
 */
class Group extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['grp_name', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => GroupModule::t('group', 'group_name')],
            [['grp_name'], 'required'],
            [['grp_name'], 'unique'],
            [['grp_name'], 'string', 'max' => 30, 'min' => 3],
            [['grp_description'], 'string', 'max' => 255],
            ['grp_description', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'grp_id' => GroupModule::t('group', 'id'),
            'grp_name' => GroupModule::t('group', 'name'),
            'grp_description' => GroupModule::t('group', 'description'),
        ];
    }

    public function canDelete($id)
    {
        /** @var Extension $extensionCount */
        $extensionCount = Extension::find()->where(['em_group_id' => $id])->count();

        if ($extensionCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
