<?php

namespace app\modules\ecosmob\emailtemplate\models;

use Yii;

/**
 * This is the model class for table "ct_email_templates".
 *
 * @property int    $id
 * @property string $key
 * @property string $subject
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class EmailTemplate extends \yii\db\ActiveRecord {
    
    /**
     * {@inheritdoc}
     */
    public static function tableName () {
        return 'ct_email_templates';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules () {
        return [
            [ [ 'key', 'subject', 'content' ], 'required' ],
            [ [ 'content' ], 'string' ],
            [ [ 'created_at', 'updated_at' ], 'safe' ],
            [ [ 'key', 'subject' ], 'string', 'max' => 255 ],
            [ [ 'key' ], 'unique' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels () {
        return [
            'id'         => 'ID',
            'key'        => 'Key',
            'subject'    => 'Subject',
            'content'    => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
