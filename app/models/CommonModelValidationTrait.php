<?php

namespace app\models;

use app\modules\ecosmob\extension\models\Extension;
use Yii;

/**
 * Trait CommonModelValidationTrait
 * @package app\models
 */
trait CommonModelValidationTrait
{
    /**
     * @param $attribute
     */
    public function validateIfSameAliasesExist($attribute)
    {
        if (!is_numeric($this->{$attribute})) {
            if ((!(int)$this->checkAliasExistInOtherAlias($this->{$attribute}) > 0)) {
                $this->addError($attribute, Yii::t('app', 'only_3_allowed')); //ext_num_exist_in_aliases
            }
        } else {
            if (strlen($this->{$attribute}) < 10) {
                $error = true;
                if (ExtensionsView::onlyUserExtensions($this->{$attribute})) {
                    $error = false;
                }
                if (((int)$this->checkAliasExistInOtherAlias($this->{$attribute}) > 0)) {
                    $error = false;
                }
                if ($error) {
                    $this->addError($attribute, Yii::t('app', 'only_3_allowed')); //ext_num_exist_in_aliases
                }
            }
        }
    }

    /**
     * @param $value
     * @return int|string
     */
    public function checkAliasExistInOtherAlias($value)
    {
        $query = Extension::find()
            ->select('em_id')
            ->where('FIND_IN_SET(:value, em_aliases) > 0')
            ->andWhere(['!=', 'em_status', 'X'])
            ->andWhere(['tm_id' => Yii::$app->user->identity->tm_id])
            ->addParams([':value' => $value]);

        // if ($this->scenario === 'update' && !$this->isNewRecord) { // update
        //     return $query->andWhere(['!=', 'em_id', $this->em_id])->count();
        // }

        return $query->count();
    }

    /**
     * @param $attribute
     */
    public function validateAgainstAliases($attribute)
    {
        if ((int)$this->checkAliasExistInOtherAlias($this->{$attribute}) > 0) {
            $this->addError($attribute, Yii::t('app', 'ext_num_exist_in_aliases', ['ext_num' => $this->{$attribute}]));
        }
    }
}
