<?php

namespace app\modules\ecosmob\auth\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "ntc_qrcode".
 *
 * @property integer $qrc_id
 * @property integer $qrc_em_id
 * @property string $qrc_qrcode
 */
class Qrcode extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ntc_qrcode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qrc_em_id', 'qrc_qrcode'], 'required'],
            [['qrc_em_id'], 'integer'],
            [['qrc_qrcode'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qrc_id' => Yii::t('app', 'Qrc ID'),
            'qrc_em_id' => Yii::t('app', 'Qrc Em ID'),
            'qrc_qrcode' => Yii::t('app', 'Qrc Qrcode'),
        ];
    }
}
