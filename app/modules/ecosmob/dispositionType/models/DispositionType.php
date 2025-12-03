<?php

namespace app\modules\ecosmob\dispositionType\models;

use app\modules\ecosmob\disposition\models\DispositionGroupStatusMapping;
use Yii;

/**
 * This is the model class for table "ct_disposition_type".
 *
 * @property int $ds_type_id auto increment id
 * @property int $ds_id Reference key from disposition master table
 * @property string $ds_type
 */
class DispositionType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_disposition_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ds_type'], 'required'],
            [['ds_type'], 'string', 'max' => 40],
            [['is_default'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ds_type_id' => Yii::t('app', 'Ds Type ID'),
            'ds_id' => Yii::t('app', 'Ds ID'),
            'ds_type' => Yii::t('app', 'Disposition Status'),
        ];
    }
    public static function getContactedDispositionStatus($id, $selectedId = [])
    {
//        if(!$id){
//            $disMappingList = DispositionGroupStatusMapping::find()->asArray()->all();
//
//        }else{
//            $disMappingList = DispositionGroupStatusMapping::find()->where(['!=','ds_group_id',$id])->asArray()->all();
//
//        }
//
//        $dispositionMapping = array_column($disMappingList, 'ds_status_id', 'ds_status_id');

        $dispositionType = self::find()->andWhere(['NOT IN', 'is_default', [1, 2]]);
       /* echo '<pre>';
        print_r($dispositionType);exit;*/
        if(!empty($selectedId)){
            $dispositionType = $dispositionType->andWhere(['NOT IN', 'ds_type_id', array_keys($selectedId)]);
        }
        $dispositionType = $dispositionType->all();
        $dsArr = [];
        foreach ($dispositionType as $ds) {
            //if (!in_array($ds->ds_type_id, $dispositionMapping)) {
                $dsArr[$ds->ds_type_id] = $ds->ds_type;
            //}
        }
        return $dsArr;
    }

    public static function getNonContactedDispositionStatus($id, $selectedId = [])
    {
        $dispositionType = self::find()->andWhere(['NOT IN', 'is_default', [1, 2]]);
        if(!empty($selectedId)){
            $dispositionType = $dispositionType->andWhere(['NOT IN', 'ds_type_id', array_keys($selectedId)]);
        }
        $dispositionType = $dispositionType->all();
        $dsArr = [];
        foreach ($dispositionType as $ds) {
            $dsArr[$ds->ds_type_id] = $ds->ds_type;
        }
        return $dsArr;
    }

    public static function getDispositionStatus($id)
    {
        $dispositionType = self::find()->where(['NOT IN', 'is_default', [1, 2]]);
        $dispositionType = $dispositionType->all();
        $dsArr = [];
        foreach ($dispositionType as $ds) {
            $dsArr[$ds->ds_type_id] = $ds->ds_type;
        }
        return $dsArr;
    }
}
