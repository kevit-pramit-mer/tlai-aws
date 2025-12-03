<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\redialcall\models\ReDialCall;
use app\modules\ecosmob\redialcall\ReDialCallModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\redialcall\assets\RedialCallAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\redialcall\ReDialCallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ReDialCallModule::t('redialcall', 're_dial_calls');

$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$model = new ReDialCall();

RedialCallAsset::register($this);
?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <?php $form = ActiveForm::begin([
                                'class' => 'row',
                                //'id'=>'submit-break-form',
                                //'action'=>['supervisor/supervisor/submit-break-reason'],
                                'fieldConfig' => [
                                    'options' => [
                                        'class' => 'input-field col s12'
                                    ],
                                ],
                            ]); ?>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col s12 m4">
                                                <div class="input-field">
                                                    <div class="select-wrapper">
                                                        <?php
                                                        $campaignList = Campaign::find()->select(['cmp_id', 'cmp_name'])->where(['cmp_status' => 'Active'])->all();
                                                        $campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');
                                                        echo $form->field($model, 'campaign', ['options' => ['class' => ''], 'inputOptions' => [
                                                            'autofocus' => true,
                                                        ],])->dropDownList($campaign, ['prompt' => ReDialCallModule::t('redialcall', 'select_campaign')])->label(ReDialCallModule::t('redialcall', 'select_campaign'));
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m4">
                                                <div class="input-field">
                                                    <div class="select-wrapper">
                                                        <?php
                                                        echo $form->field($model, 'ld_group_name', ['options' => ['class' => '']])->dropDownList(['prompt' => ReDialCallModule::t('redialcall', 'select_lead_group')])->label(ReDialCallModule::t('redialcall', 'select_lead_group'));

                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m4">
                                                <div class="input-field">
                                                    <div class="select-wrapper">
                                                        <?php
                                                        echo $form->field($model, 'disposition', ['options' => ['class' => '']])->dropDownList(['prompt' => ReDialCallModule::t('redialcall', 'select_disposition_list')])->label(ReDialCallModule::t('redialcall', 'select_disposition_list'));
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="lead-count" class="col s12" style="min-height: 147px;">
                                        </div>
                                        <div class="hseparator"></div>
                                        <div class="col s12 p-0">
                                            <div class="input-field mrg-btn">
                                                <?= Html::Button(ReDialCallModule::t('redialcall', 'update'), [
                                                    'class' => 'btn waves-effect waves-light amber darken-4 update-btn',
                                                    'id' => 'update-lead-status',
                                                    'style' => 'margin-bottom: 15px',
                                                    'name' => 'apply',
                                                    'disabled' => 'disabled',
                                                    'value' => 'update']) ?>
                                            </div>
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function leadStatusCount() {
        $('#update-lead-status').attr('disabled', 'disabled');
        var redial_dispo_id = $('#redialcall-disposition').find(':selected').val();
        var redial_ld_id = $('#redialcall-ld_group_name').find(':selected').val();
        var campId = $('#redialcall-campaign').find(':selected').val();
        //var redial_ld_id = $('#redialcall-ld_group_name').find(':selected').val();
        $('#lead-count').html('');
        if (redial_dispo_id == '<?= ReDialCallModule::t('redialcall', 'select_disposition_list') ?>') {
            $('#lead-count').html('');
            $('#update-lead-status').attr('disabled', 'disabled');
            return false;
        }

        if (redial_dispo_id != '') {
            $.ajax({
                type: 'POST',
                url: baseURL + "index.php?r=redialcall/re-dial-call/lead-status-count",
                data: {redial_dispo_id: redial_dispo_id, redial_ld_id: redial_ld_id, campId: campId},
                success: function (result) {
                    $('#lead-count').html(result);
                    if (result != "") {
                        $('#update-lead-status').removeAttr('disabled');
                    }
                }
            });
        } else {
            return false;
        }
    }

    $(document).on('change', '#redialcall-disposition', function () {
        leadStatusCount();
    });

    //For Campaign

    $('#redialcall-campaign').change(function () {
        $('#redialcall-ld_group_name').html('<option> <?=ReDialCallModule::t('redialcall', 'select_lead_group')?> </option>');
        $('#redialcall-disposition').html('<option> <?=ReDialCallModule::t('redialcall', 'select_disposition_list')?> </option>');
        $('#lead-count').html('');
        $('#update-lead-status').attr('disabled', 'disabled');
        var redial_cmp_id = $(this).find(':selected').val();
        if (redial_cmp_id != '') {
            $.ajax({
                url: baseURL + "index.php?r=redialcall/re-dial-call/leadgroup-list",
                type: 'post',
                data: {
                    'redial_cmp_id': redial_cmp_id
                },
                success: function (data) {
                    $('#redialcall-ld_group_name').html(data);
                }
            });
        }

    });

    // For Lead Master Group
    $('#redialcall-ld_group_name').change(function () {
        $('#redialcall-disposition').html('<option> <?= ReDialCallModule::t('redialcall', 'select_disposition_list') ?> </option>');
        $('#lead-count').html('');
        $('#update-lead-status').attr('disabled', 'disabled');
        var redial_ld_id = $(this).find(':selected').val();
        var campId = $('#redialcall-campaign').find(':selected').val();
        if (redial_ld_id != '') {
            $.ajax({
                url: baseURL + "index.php?r=redialcall/re-dial-call/disposition-list",
                type: 'post',
                data: {
                    'redial_ld_id': redial_ld_id,
                    'campId': campId
                },
                success: function (data) {
                    $('#redialcall-disposition').html(data);
                }
            });
        }

    });

    function updateLeadStatus() {
        var redial_ld_id = $('#redialcall-ld_group_name').find(':selected').val();
        var dispo_ld_id = $('#redialcall-disposition').find(':selected').val();
        var campId = $('#redialcall-campaign').find(':selected').val();

        $.ajax({
            type: 'POST',
            url: baseURL + "index.php?r=redialcall/re-dial-call/update-lead-status",
            data: {redial_ld_id: redial_ld_id, redial_dipso_id: dispo_ld_id, campId: campId},
            success: function (result) {
                leadStatusCount();
                //var final_data = JSON.parse(result);
                //alert(final_data.msg);
                alert('<?php echo ReDialCallModule::t('redialcall', 'updated_success'); ?>');
            }
        });
    }

    function updateNewLeadStatus() {
        var redial_ld_id = $('#redialcall-ld_group_name').find(':selected').val();
        var dispo_ld_id = $('#redialcall-disposition').find(':selected').val();
        var campId = $('#redialcall-campaign').find(':selected').val();

        $.ajax({
            type: 'POST',
            url: baseURL + "index.php?r=redialcall/re-dial-call/update-new-lead-status",
            data: {redial_ld_id: redial_ld_id, redial_dipso_id: dispo_ld_id, campId: campId},
            success: function (result) {
                leadStatusCount();
            }
        });
    }

    function updateBlendedNewLeadStatus() {
        var redial_ld_id = $('#redialcall-ld_group_name').find(':selected').val();
        var dispo_ld_id = $('#redialcall-disposition').find(':selected').val();
        var campId = $('#redialcall-campaign').find(':selected').val();
        $.ajax({
            type: 'POST',
            url: baseURL + "index.php?r=redialcall/re-dial-call/update-blended-new-lead-status",
            data: {redial_ld_id: redial_ld_id, redial_dipso_id: dispo_ld_id, campId: campId},
            success: function (result) {
                leadStatusCount();
            }
        });
    }

    $(document).on('click', '#update-lead-status', function () {
        updateLeadStatus();
        updateNewLeadStatus();
        updateBlendedNewLeadStatus();
    });
</script>

