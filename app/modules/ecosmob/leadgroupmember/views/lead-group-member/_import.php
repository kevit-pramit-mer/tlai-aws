<?php

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\leadgroupmember\assets\LeadGroupMemberAsset;

/* @var $this yii\web\View */
/* @var $importModel app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */
/* @var $form yii\widgets\ActiveForm */

LeadGroupMemberAsset::register($this);
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
                <div class="lead-group-member-import-form" id="lead-group-member-import-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><?=
                                        LeadGroupMemberModule::t('lead-group-member', 'file') ?>
                                    </span>
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
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'cancel'),
                    ['index', 'ld_id' => $importModel->ld_id, 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(LeadGroupMemberModule::t('lead-group-member', 'import'),
                    ['class' => 'btn waves-effect waves-light amber darken-4']) ?>
                <?php echo Html::a(LeadGroupMemberModule::t('lead-group-member', 'download_sample_file'),
                    ['download-sample-file'],
                    ['data-pjax' => 0, 'class' => 'btn waves-effect waves-light amber darken-4']); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>