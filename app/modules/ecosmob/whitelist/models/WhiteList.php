<?php

namespace app\modules\ecosmob\whitelist\models;

use Yii;
use app\modules\ecosmob\whitelist\WhiteListModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_whitelist".
 *
 * @property int $wl_id
 * @property string $wl_number
 * @property string $wl_description
 * @property string $updated_date
 * @property string $created_date
 */
class WhiteList extends ActiveRecord
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
                'wl_number' => [
                    'displayName' => 'White List Number',
                    'sample' => '1212121212',
                ],
                'wl_description' => [
                    'displayName' => 'Description',
                    'sample' => 'Grant this user',
                ],
            ],

        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_whitelist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wl_number'], 'required'],
            ['wl_number', 'number' ],
            ['wl_number', 'unique'],
            [['wl_number',], 'match', 'pattern' => '/^[0-9]+$/',],
            [['wl_number'], 'string', 'max' => 20, 'min' => 3, 'tooLong' => WhiteListModule::t('wl', 'wl_number_max_validation'), 'tooShort' => WhiteListModule::t('wl', 'wl_number_min_validation')],
            [['wl_description'], 'string', 'max' => 110],
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
            'wl_id' => WhiteListModule::t('wl', 'wl_id'),
            'wl_number' => WhiteListModule::t('wl', 'wl_number'),
            'wl_description' => WhiteListModule::t('wl', 'wl_description'),
            'updated_date' => WhiteListModule::t('wl', 'updated_date'),
            'created_date' => WhiteListModule::t('wl', 'created_date'),
            'importFileUpload' => WhiteListModule::t('wl', 'importFileUpload'),
        ];
    }
}
