<?php

namespace app\modules\ecosmob\callrecordings\models;

use app\modules\ecosmob\callrecordings\CallRecordingsModule;
use Yii;

/**
 * This is the model class for table "ct_call_recordings".
 *
 * @property int $cr_id
 * @property string $cr_name
 * @property string $cr_files
 * @property string $cr_date
 */
class CallRecordings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_call_recordings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cr_name'], 'required'],
            [['cr_date'], 'safe'],
            [['cr_name'], 'string', 'max' => 211],
            [['cr_files'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cr_id' => CallRecordingsModule::t('app', 'Cr ID'),
            'cr_name' => CallRecordingsModule::t('app', 'Cr Name'),
            'cr_files' => CallRecordingsModule::t('app', 'Cr Files'),
            'cr_date' => CallRecordingsModule::t('app', 'Cr Date'),
        ];
    }
}
