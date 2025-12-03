<?php

use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $fields */

$this->title = IpprovisioningModule::t('app', 'settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'device_templates'), 'url' => ['index'],];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'settings');
$this->params['pageHead'] = $this->title;
?>

<style>
    input[type=text]:not(.browser-default):disabled {
        background: rgb(236 238 241);
    }
</style>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="devise-settings">
                    <div class="row">
                        <div class="col s12">
                            <?php $form = ActiveForm::begin([
                                'class' => 'row',
                                'fieldConfig' => [
                                    'options' => [
                                        'class' => 'input-field',
                                    ],
                                ],
                            ]); ?>
                            <div id="input-fields" class="card card-tabs">
                                <div class="form-card-header">
                                    <?= $this->title ?>
                                </div>
                                <div class="card-content">

                                    <?php $i = 0;
                                    $rowOpen = false;
                                    foreach ($fields as $field): ?>
                                        <?php if ($i == 0) {
                                            if ($rowOpen) {
                                                echo '</div>';
                                            }
                                            echo '<div class="row">';
                                            $rowOpen = true;
                                        } ?>
                                        <div class="col s4">
                                            <div class="input-field">
                                                <?= Html::label($field->parameter_label, $field->parameter_name) ?>
                                                <?php
                                                if ($field->input_type === 'checkbox') {
                                                    $checked = $field->parameter_value == 'true';
                                                    echo Html::checkbox($field->id, $checked, [
                                                        'value' => 'true',
                                                        'uncheck' => 'false',
                                                        'id' => $field->parameter_name,
                                                        'disabled' => $field->is_writable == '0' ? true : false
                                                    ]);
                                                } else {
                                                    echo Html::input('text', $field->id, $field->parameter_value, ['id' => $field->parameter_name, 'class' => 'form-control', 'disabled' => $field->is_writable == '0' ? true : false]);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                        if ($i == 3) {
                                            $i = 0;
                                            echo '</div>';
                                            $rowOpen = false;
                                        }
                                    endforeach;

                                    if ($rowOpen) {
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col s12 pb-3 d-flex align-items-center gap-10">
                                    <?= Html::a(IpprovisioningModule::t('app', 'cancel'),
                                        ['index', 'page' => Yii::$app->session->get('page')],
                                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                                    <?= Html::submitButton(IpprovisioningModule::t('app', 'submit'),
                                        [
                                            'class' => 'btn waves-effect waves-light amber darken-4'
                                        ]) ?>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>