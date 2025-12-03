<?php

use app\modules\ecosmob\dbbackup\DbBackupModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="audio-management-form"
                     id="audio-management-form">

                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'enctype' => 'multipart/form-data',
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s6">
                          			<div class="file-field input-field" id="inputfile_">
				                <div class="col s12">
				                    <div class="col s12">
				                        <!-- span class="new badge red"
				                              data-badge-caption="<?= DbBackupModule::t('am', 'only_mp3_wav_file_supported') ?>"></span -->
				                    </div>
				                    <div class="btn">
				                        <span><?= DbBackupModule::t('app', 'file') ?></span>
				                        <?= $form->field($model, 'db_name',
				                            ['options' => ['class' => '']]
				                        )->fileInput(['accept' => '.zip'])->label(false); ?>
				                    </div>
				                    <div class="file-path-wrapper">
				                        <input class="file-path validate" type="text"
				                               value="<?php if (!$model->isNewRecord) {
				                                   echo $model->db_name;
				                               } ?>">
				                    </div>
				                </div>					    
				            </div>
					 
                        </div>
		        <div class="col s6">                           
                        </div>
			  <div class="col s12">    
			     <div style="color: red;"><?php echo DbBackupModule::t('app', 'note'); ?>: 
				   <br/>- <?php echo DbBackupModule::t('app', 'note1'); ?>
				   <br/>- <?php echo DbBackupModule::t('app', 'note2'); ?>
				   <br/>- <?php echo DbBackupModule::t('app', 'note3'); ?>
				   <br/>- <?php echo DbBackupModule::t('app', 'note4'); ?>
			    </div> 	
		         </div> 	
                    </div>
                    

                    <div class="hseparator"></div>
                    <div class="row">
                        <div class="col s12 center">
                            <div class="input-field">
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') :
                                    Yii::t('app', 'update'),
                                    [
                                        'id' => 'submitbtn',
                                        'class' => $model->isNewRecord
                                            ? 'btn waves-effect waves-light amber darken-4'
                                            :
                                            'btn waves-effect waves-light cyan accent-8'
                                    ]) ?>
                                <?php if (!$model->isNewRecord) { ?>
                                    <?= Html::submitButton(Yii::t('app', 'apply'), [
                                        'class' => 'btn waves-effect waves-light  amber darken-4',
                                        'name' => 'apply',
                                        'value' => 'update']) ?>
                                <?php } ?>
                                <?= Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="isNewRecord" value=" <?= $model->isNewRecord ?>">

<script>
    $(document).ready(function () {
        checkType();
    });
    $(document).on('change', '#audiomanagement-af_type', function () {
        checkType();
    });

    function checkType() {
        if ($('#audiomanagement-af_type').val() == 'Recording') {
            if ($('#isNewRecord').val() == 1) {
                $('#submitbtn').text('<?= DbBackupModule::t('am', 'call_and_create'); ?>');
            }
            $('#inputfile_').slideUp();
            $('#ext_').slideDown();
        } else {
            if ($('#isNewRecord').val() == 1) {
                $('#submitbtn').text('<?= Yii::t('app','create'); ?>');
            }
            $('#inputfile_').slideDown();
            $('#ext_').slideUp();
        }
    }
</script>


<style>
    .help-block {
        color: #d32f2f !important;
        font-size: small;
        position: relative;
        bottom: 8px !important;
        text-transform: capitalize;
    }

    @media screen and (max-width: 1366px) {
        .help-block {
            color: #d32f2f !important;
            font-size: small;
            position: absolute;
            top: 59px !important;
            text-transform: capitalize;
        }
    }
</style>
