<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \yii2mod\rbac\models\BizRuleModel */
/* @var $form ActiveForm */
?>

<div class="rule-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="form-group">
            <?php echo $form->field($model, 'name',
                ['options' => ['class' => 'col s12']])->textInput(['maxlength' => 64]); ?>

            <?php echo $form->field($model, 'className',
                ['options' => ['class' => 'col s12']])->textInput(); ?>

        </div>
    </div>
    <div class="hseparator"></div>
    <div class="form-group col s12 center">
        <?php echo Html::submitButton($model->getIsNewRecord() ? Yii::t('yii2mod.rbac',
            'Create') : Yii::t('yii2mod.rbac', 'Update'),
            ['class' => $model->getIsNewRecord() ? 'btn btn-success btn-round-left' : 'btn btn-primary btn-round-left']); ?>

        <?php
        echo Html::a(Yii::t('app', 'cancel'), 'index', ['class' => 'btn btn-danger btn-round-right']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
