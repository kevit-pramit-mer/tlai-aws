<?php

namespace app\modules\ecosmob\playback\models;

use app\modules\ecosmob\playback\PlaybackModule;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_playback".
 *
 * @property int    $pb_id
 * @property string $pb_name
 * @property string $pb_language
 * @property string $pb_file
 */
class Playback extends \yii\db\ActiveRecord {
    
    /**
     * {@inheritdoc}
     */
    public static function tableName () {
        return 'ct_playback';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules () {
        return [
            [ [ 'pb_name', 'pb_language', 'pb_file' ], 'required' ],
            [ [ 'pb_language' ], 'string' ],
            [ [ 'pb_name' ], 'string', 'max' => 30 ],
            [ [ 'pb_name','pb_file' ], 'unique' ],
            [
                [ 'pb_file' ],
                'file',
                'extensions'               => 'mp3, wav',
                'checkExtensionByMimeType' => FALSE,
                'maxSize'                  => 10485760, //10 MB
                'minSize'                  => 0.0004882813, //0.5kb
                'tooBig'                   => PlaybackModule::t( 'pb', 'limit_is_10_mb' ),
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels () {
        return [
            'pb_id'        => PlaybackModule::t( 'pb', 'id' ),
            'pb_name'      => PlaybackModule::t( 'pb', 'name' ),
            'pb_language'  => PlaybackModule::t( 'pb', 'language' ),
            'pb_file'      => PlaybackModule::t( 'pb', 'file' ),
        ];
    }
    
    /**
     * @return array
     */
    public static function getPlaybackFiles() {
        return ArrayHelper::map(static::find()->orderBy(['pb_id' => SORT_DESC])->all(), 'pb_id' , 'pb_name');
    }
}
