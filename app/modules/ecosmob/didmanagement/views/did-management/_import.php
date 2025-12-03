<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\didmanagement\assets\DidAsset;

/* @var $this yii\web\View */
/* @var $importModel app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $form yii\widgets\ActiveForm */

DidAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field col s12',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="didmaster-form" id="didmaster-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><?= DidManagementModule::t('did', 'file') ?></span>
                                    <?= $form->field($importModel,
                                        'importFileUpload',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])->fileInput(['accept' => '.csv'])->label(FALSE) ?>
                                </div>&nbsp;<span style="color: red;">*</span>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- <div class="row input-field">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?php /*= $form->field($importModel, 'action_id', ['options' => ['class' => '']])
                                    ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                    ->label($importModel->getAttributeLabel('action_id')); */?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <div id="">
                                    <?php /*= $form->field($importModel,
                                        'action_value',
                                        ['options' => ['class' => '', 'id' => 'select_action_value']])
                                        ->dropDownList([], ['prompt' => Yii::t('app', 'Select')])
                                        ->label($importModel->getAttributeLabel('action_value')); */?>
                                </div>

                                <?php /*= $form->field($importModel, 'action_value')
                                    ->textInput(['maxlength' => TRUE, 'id' => 'input_action_value'])
                                    ->label($importModel->getAttributeLabel('action_value')); */?>
                            </div>
                        </div>
                    </div>-->
                    <!--   <div class="row">
                        <div class="col m6">
                            <div class="input-field col s12">

                                <? /*= $form->field( $importModel,
                                    'fax',
                                    [ 'options' => [ 'class' => '', 'id' => 'select_action_value' ] ] )
                                    ->dropDownList( Fax::getFax(), [ 'prompt' => Yii::t( 'app', 'Select' ) ] )
                                    ->label( $importModel->getAttributeLabel( 'fax' ) ); */ ?>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(DidManagementModule::t('did', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) 
                ?>
                <?php echo Html::a(DidManagementModule::t('did', 'download_sample_file'),
                    ['download-sample-file'],
                    [
                        'data-pjax' => 0,
                        'class' => 'btn waves-effect waves-light amber darken-4',
                    ]); 
                ?>
                <?= Html::submitButton(DidManagementModule::t('did', 'import'),
                    [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                    ]) 
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<Script>
    var action_value = '<?= $importModel->action_value ?>';

    $(document).ready(function () {
        $('.field-input_action_value').hide();
        $('#select_action_value').hide();

        setTimeout(function () {
            changeAction(action_value);
        }, 500);
    });

    $(document).on('change', '#didmanagement-action_id', function () {
        changeAction('');
    });

    function changeAction(action_value) {
        var action_id = $('#didmanagement-action_id').val();

        if (action_id != '') {
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {'action_id': action_id, 'action_value': action_value},
                success: function (data) {
                    if (action_id == '6') { // external
                        // show textbox
                        $('.field-input_action_value').show();
                        // remove disabled from textbox
                        $('#input_action_value').removeAttr('disabled');
                        $('#input_action_value').val(action_value);

                        // hide select
                        $('#select_action_value').hide();
                        // add disabled in input
                        // add disabled in input
                        $('#didmanagement-action_value').attr('disabled', 'disabled');


                    } else {
                        $('#input_action_value').attr('disabled', 'disabled');
                        $('#didmanagement-action_value').removeAttr('disabled');
                        $('#didmanagement-action_value').html(data);
                        $('select').formSelect();
                        $('#select_action_value').show();
                        $('.field-input_action_value').hide();
                    }

                    $('select').select2('destroy');
                    $('select').select2();


                }
            });
        }
    }

</Script>
