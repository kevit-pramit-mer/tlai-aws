<?php

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\supervisor\SupervisorModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisor\models\AdminMasterSearch */
/* @var $form yii\widgets\ActiveForm */
$extensionlists = Extension::find()->where(['em_status' => '1'])->all();
foreach ($extensionlists as $ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}

$ext = ArrayHelper::map($extensionlists, 'em_id', 'em_extension_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="admin-master-search"
                     id="admin-master-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'admin-master-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'adm_firstname')->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_firstname')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'adm_lastname')->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_lastname')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'uname')->textInput( [ 'placeholder' => ($model->getAttributeLabel('uname')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'adm_email')->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_email')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'adm_status', ['options' => ['class' => '']])->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')], ['prompt' => SupervisorModule::t('supervisor', 'select_status')])->label(SupervisorModule::t('supervisor', 'status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'adm_mapped_extension', ['options' => ['class' => '']])->dropDownList
                                ($ext,
                                    ['prompt' => SupervisorModule::t('supervisor', 'select_extension')])->label(SupervisorModule::t('supervisor', 'extension')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(SupervisorModule::t('supervisor', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(SupervisorModule::t('supervisor', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
