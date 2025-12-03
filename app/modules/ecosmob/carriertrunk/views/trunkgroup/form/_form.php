<?php

use app\modules\ecosmob\carriertrunk\assets\SipTrunkAsset;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\carriertrunk\models\TrunkGroupDetails;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroup */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroupDetails */
/* @var $form yii\widgets\ActiveForm */
/* @var $trunkMaster */
SipTrunkAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'trunk-group-form',
                'class' => 'form-horizontal',
                'fieldConfig' => [
                    'options' => [
                        'class' => 'input-field',
                    ],
                ],
            ]
        ); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>    
            <div class="card-content">
                <div class="trunk-group-form" id="trunkgroup-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_grp_name', ['inputOptions' => [
                                        'class' => 'form-control',
                                    ]])->textInput(
                                    [
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_grp_name'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_grp_desc')->textArea(
                                    [
                                        'maxlength' => TRUE,
                                        'class' => 'materialize-textarea',
                                        'placeholder' => ($model->getAttributeLabel('trunk_grp_desc'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?php
                                if (!$model->isNewRecord) {
                                    echo $form->field(
                                        $model,
                                        'trunk_grp_status',
                                        ['options' => ['class' => '']]
                                    )->dropDownList(
                                        [
                                            '1' => CarriertrunkModule::t('carriertrunk', 'active'),
                                            '0' => CarriertrunkModule::t('carriertrunk', 'inactive'),
                                        ]
                                    );
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col s12"
                               for="lstBox1"><?php /*= CarriertrunkModule::t(
                                'carriertrunk',
                                'trunk'
                            ) */?></label>
                        <div class="col s5">
                            <div class="form-group field-groups-group_status required align-items-center">
                                <div class="col s12 no-padding">
                                    <label class="control-label"
                                           for="groups-group_status"><b><?= CarriertrunkModule::t(
                                                'carriertrunk',
                                                'trunk'
                                            ).' '. CarriertrunkModule::t('carriertrunk', 'available') ?></b></label>
                                </div>
                                <select multiple="multiple" id='lstBox1'
                                        class="multiselect form-control list" size="10"
                                        data-target="avaliable">

                                    <?php if ($model->isNewRecord):
                                        foreach ($trunkMaster as $trunk) {
                                            echo "<option value='" . $trunk["trunk_id"]
                                                . "'>" . $trunk["trunk_display_name"] . "</option>";
                                        }
                                    else :
                                        if (!empty($left_trunks)) {
                                            foreach ($left_trunks as $trunk) {
                                                echo "<option value='"
                                                    . $trunk["trunk_id"] . "'>"
                                                    . $trunk["trunk_display_name"] . "</option>";
                                            }
                                        }
                                    endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col s2 btn_div multiselect-btn-pad p-0">
                            <button type="button" id="btnRight"
                                    class="btn-margin-bottom btn btn-block multiselect_btn"
                                    data-target='avaliable'>
                                <i class="material-icons">keyboard_arrow_right</i>
                            </button>
                            <button type="button" id="btnLeft"
                                    class="btn-margin-bottom btn btn-block multiselect_btn"
                                    data-target='assigned'>
                                <i class="material-icons">keyboard_arrow_left</i>
                            </button>
                        </div>
                        <div class="col s5">
                            <div class="form-group field-groups-group_status required">
                                <div class="col s12 no-padding">
                                    <label class="control-label"
                                           for="groups-group_status"><b><?= CarriertrunkModule::t(
                                                'carriertrunk',
                                                'trunk'
                                            ).' '. CarriertrunkModule::t('carriertrunk', 'assigned') ?></b></label>
                                </div>
                                <select multiple="multiple" name='lstBox2' id='lstBox2'
                                        class="multiselect form-control list" size="10"
                                        data-target="assigned">
                                    <?php if ($model->isNewRecord):
                                        foreach ([] as $trunk) {
                                            echo "<option value='" . $trunk["trunk_id"]
                                                . "'>" . $trunk["trunk_display_name"] . "</option>";
                                        }
                                    else :
                                        if (!empty($right_trunks)) {
                                            foreach ($right_trunks as $trunk) {
                                                $name = TrunkGroupDetails::getTrunkName(
                                                    $trunk
                                                );
                                                echo "<option value='" . $trunk . "'>" . $name
                                                    . "</option>";
                                            }
                                        }
                                    endif; ?>
                                </select>

                                <?= $form->field($model, 'lstBox3')->hiddenInput(
                                    ['id' => 'lstBox3']
                                )->label(FALSE) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?php if ($model->isNewRecord) {
                        echo Html::a(
                            CarriertrunkModule::t('carriertrunk', 'cancel'),
                            ['index'],
                            ['class' => 'btn waves-effect waves-light bg-gray-200']
                        );
                    } else {
                        echo Html::a(
                            CarriertrunkModule::t('carriertrunk', 'cancel'),
                            Yii::$app->session->get('tgroup_redirect_to'),
                            ['class' => 'btn waves-effect waves-light bg-gray-200']
                        );
                    } ?>
                    <?= Html::submitButton(
                        $model->isNewRecord ? CarriertrunkModule::t(
                            'carriertrunk',
                            'create'
                        ) : CarriertrunkModule::t('carriertrunk', 'update'),
                        [
                            'class' => $model->isNewRecord
                                ? 'btn waves-effect waves-light amber darken-4'
                                : 'btn waves-effect waves-light cyan accent-8',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.field-groups-group_status select').formSelect('destroy');
        $('.field-groups-group_status select').css('display', 'block');
        $('.field-groups-group_status select').css('height', '200px');
        $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');
    });
</script>
