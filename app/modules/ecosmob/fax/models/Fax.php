<?php

namespace app\modules\ecosmob\fax\models;

use app\modules\ecosmob\fax\FaxModule;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fax".
 *
 * @property int $id
 * @property string $fax_name
 * @property string $fax_destination
 * @property string $fax_failure
 */
class Fax extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fax';
    }

    public static function getFax()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'fax_name');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fax_name', 'fax_destination', 'fax_extension'], 'required'],
            ['fax_name', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => FaxModule::t('fax', 'fax_name_error')],
//            ['fax_description', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => FaxModule::t('fax','fax_destination')],

            [['fax_destination'], 'email'],
            [['fax_destination', 'fax_extension'], 'safe'],
            [['fax_destination', 'fax_name'], 'unique'],
            [['fax_name'], 'string', 'max' => 30, 'min' => 3],
            [['fax_destination'], 'string', 'max' => 250, 'min' => 10],
            [['fax_extension'], 'number'],
            [['fax_extension'], 'string', 'max' => 20, 'min' => 3, 'tooLong'=> FaxModule::t('fax', 'fax_extension_max_validation'), 'tooShort' => FaxModule::t('fax', 'fax_extension_min_validation')],
            [['fax_extension'], 'unique'],
            [['fax_extension'], 'checkUnique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fax_name' => FaxModule::t('fax', 'fax_name'),
            'fax_destination' => FaxModule::t('fax', 'fax_destination'),
            'fax_failure' => FaxModule::t('fax', 'fax_failure'),
            'fax_extension' => FaxModule::t('fax', 'fax_extension'),
        ];
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->fax_extension, $this->getOldAttribute('fax_extension'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }

}
