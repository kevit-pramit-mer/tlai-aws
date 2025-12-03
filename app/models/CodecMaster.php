<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_codec_master".
 *
 * @property integer $ntc_codec_id
 * @property string $ntc_codec_name
 * @property string $ntc_codec_desc
 * @property string $ntc_codec_type
 */
class CodecMaster extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_codec_master';
    }

    /**
     * Returns list of all Audio codec.
     *
     * @return array
     */
    public static function getAllAudioCodec()
    {
        return ArrayHelper::map(
            CodecMaster::findAll(['ntc_codec_type' => 'Audio']),
            'ntc_codec_id',
            'ntc_codec_name'
        );
    }

    /**
     * Returns list of all Video codec.
     *
     * @return array
     */
    public static function getAllVideoCodec()
    {
        return ArrayHelper::map(
            CodecMaster::findAll(['ntc_codec_type' => 'Video']),
            'ntc_codec_id',
            'ntc_codec_name'
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['ntc_codec_name', 'ntc_codec_desc', 'ntc_codec_type'],
                'required',
            ],
            [['ntc_codec_type'], 'string'],
            [['ntc_codec_name'], 'string', 'max' => 15],
            [['ntc_codec_desc'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ntc_codec_id' => Yii::t('app', 'Ntc Codec ID'),
            'ntc_codec_name' => Yii::t('app', 'Ntc Codec Name'),
            'ntc_codec_desc' => Yii::t('app', 'Ntc Codec Desc'),
            'ntc_codec_type' => Yii::t('app', 'Ntc Codec Type'),
        ];
    }
}
