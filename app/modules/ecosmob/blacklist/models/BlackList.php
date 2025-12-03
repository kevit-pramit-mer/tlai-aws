<?php

namespace app\modules\ecosmob\blacklist\models;

use Yii;
use app\modules\ecosmob\blacklist\BlackListModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_blacklist".
 *
 * @property int $bl_id
 * @property string $bl_number
 * @property string $bl_type
 * @property string $bl_reason
 * @property string $updated_date
 * @property string $created_date
 */
class BlackList extends ActiveRecord
{

    /**
     * @var
     */
    public $importFileUpload;

    /**
     * @var array
     */
    public $displayNames = [];
    /**
     * @var array
     */
    public $sampleValues = [];

    /**
     * @var array
     */
    public $import
        = [
            'fields' => [
                'bl_number' => [
                    'displayName' => 'Black List Number',
                    'sample' => '999999999',
                ],
                'bl_type' => [
                    'displayName' => 'Type',
                    'sample' => 'OUT',
                ],
                'bl_reason' => [
                    'displayName' => 'Reason',
                    'sample' => 'Some reason to blacklist',
                ],
            ],

        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_blacklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bl_number', 'bl_type'], 'required'],
            [['bl_number'], 'number' ],
            [['bl_number',], 'match', 'pattern' => '/^[0-9]+$/',],
            ['bl_number', 'unique'],
            [['bl_type'], 'string'],
            [['bl_type'], 'in', 'range' => ['IN', 'OUT', 'BOTH'], 'message' => BlackListModule::t('bl', 'typeError')],
            [['bl_number'], 'string', 'max' => 20, 'min' => 4, 'tooLong' => BlackListModule::t('bl', 'bl_number_max_validation'), 'tooShort' => BlackListModule::t('bl', 'bl_number_min_validation')],
            [['bl_reason'], 'string', 'max' => 110],
            [['updated_date', 'created_date'], 'string', 'max' => 40],
            [
                ['importFileUpload'],
                'required',
                'on' => 'import',
            ],
            [
                ['importFileUpload'],
                'file',
                'extensions' => 'csv',
                'checkExtensionByMimeType' => FALSE,
                'maxSize' => 10485760,
                'tooBig' => 'Limit is 10MB',
                'on' => 'import',
                'wrongExtension'=> Yii::t('app', 'wrongImportExtFile'),
            ],
            [['sampleValues', 'importFileUpload'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bl_id' => BlackListModule::t('bl', 'bl_id'),
            'bl_number' => BlackListModule::t('bl', 'bl_number'),
            'bl_type' => BlackListModule::t('bl', 'bl_type'),
            'bl_reason' => BlackListModule::t('bl', 'bl_reason'),
            'updated_date' => BlackListModule::t('bl', 'updated_date'),
            'created_date' => BlackListModule::t('bl', 'created_date'),
            'importFileUpload' => BlackListModule::t('bl', 'importFileUpload'),
        ];
    }
}
