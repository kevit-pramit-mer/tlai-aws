<?php

namespace app\modules\ecosmob\extension\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_findme_followme".
 *
 * @property int $ff_id Auto-increment Id
 * @property int $ff_extension Extension ID
 * @property string $ff_1_type
 * @property string $ff_1_extension
 * @property string $ff_2_type
 * @property string $ff_2_extension
 * @property string $ff_3_type
 * @property string $ff_3_extension
 */
class FindmeFollowme extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_findme_followme';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ff_1_type', 'ff_2_type', 'ff_3_type'], 'string', 'max' => 30],
            [
                'ff_1_extension',
                'required',
                'when' => function ($model) {
                    return ($model->ff_1_type == 'INTERNAL' || $model->ff_1_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_1_type').val() == 'INTERNAL' || $('#findmefollowme-ff_1_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ff_2_extension',
                'required',
                'when' => function ($model) {
                    return ($model->ff_2_type == 'INTERNAL' || $model->ff_2_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_2_type').val() == 'INTERNAL' || $('#findmefollowme-ff_2_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ff_3_extension',
                'required',
                'when' => function ($model) {
                    return ($model->ff_3_type == 'INTERNAL' || $model->ff_3_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_3_type').val() == 'INTERNAL' || $('#findmefollowme-ff_3_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                ['ff_1_extension'],
                'match',
                'pattern' => '/^[+0-9]{4,15}$/',
                'message' => 'Its contain number and + sign only with min 4 number & max 15',
                'when' => function ($model) {
                    return ($model->ff_1_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_1_type').val() == 'EXTERNAL');
                    }"
            ],

            [
                ['ff_2_extension'],
                'match',
                'pattern' => '/^[+0-9]{4,15}$/',
                'message' => 'Its contain number and + sign only with min 4 number & max 15',
                'when' => function ($model) {
                    return ($model->ff_2_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_2_type').val() == 'EXTERNAL');
                    }"
            ],

            [
                ['ff_3_extension'],
                'match',
                'pattern' => '/^[+0-9]{4,15}$/',
                'message' => 'Its contain number and + sign only with min 4 number & max 15',
                'when' => function ($model) {
                    return ($model->ff_3_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#findmefollowme-ff_3_type').val() == 'EXTERNAL');
                    }"
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ff_extension' => Yii::t('app', 'extension'),
            'ff_1_type' => Yii::t('app', 'ff_1_type'),
            'ff_1_extension' => Yii::t('app', 'ff_1_extension'),
            'ff_2_type' => Yii::t('app', 'ff_2_type'),
            'ff_2_extension' => Yii::t('app', 'ff_2_extension'),
            'ff_3_type' => Yii::t('app', 'ff_3_type'),
            'ff_3_extension' => Yii::t('app', 'ff_3_extension'),
        ];
    }
}
