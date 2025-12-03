<?php

use app\modules\ecosmob\carriertrunk\assets\SipTrunkAsset;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $availableAudioCodecs app\models\CodecMaster */
/* @var $availableVideoCodecs app\models\CodecMaster */
/* @var $audioCodec app\models\CodecMaster */
/* @var $videoCodec app\models\CodecMaster */
/* @var $availableAudioCodecUpdate app\models\CodecMaster */
/* @var $availableVideoCodecUpdate app\models\CodecMaster */

SipTrunkAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'trunk-master-form',
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
                <div class="trunk-master-form" id="trunkmaster-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'trunk_name')->textInput(
                                    [
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_name'))
                                    ]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field(
                                    $model,
                                    'trunk_ip_version',
                                    [
                                        'options' => [
                                            'class' => '',
                                            'id' => 'trunk_ip_version',
                                        ],
                                    ]
                                )->dropDownList(
                                    [
                                        'IPv4' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'IPv4'
                                        ),
                                        'IPv6' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'IPv6'
                                        ),
                                        'domain' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'domain'
                                        ),
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 ">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_ip')->textInput(
                                    [
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_ip'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_proxy_ip')->textInput(
                                    [
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_proxy_ip'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_port')->textInput(
                                    [
                                        'maxlength' => TRUE,
                                        'onkeypress' => 'return isNumberKey(event);',
                                        'placeholder' => ($model->getAttributeLabel('trunk_port'))
                                    ]
                                ); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field(
                                    $model,
                                    'trunk_register',
                                    [
                                        'options' => [
                                            'class' => '',
                                            'id' => 'reg_id',
                                        ],
                                    ]
                                )->dropDownList(
                                    [
                                        '1' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'yes'
                                        ),
                                        '0' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'no'
                                        ),
                                    ],
                                    [
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field(
                                    $model,
                                    'trunk_ip_type',
                                    [
                                        'options' => [
                                            'class' => '',
                                        ],
                                    ])->dropDownList(
                                    [
                                        'PRIVATE' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'private'
                                        ),
                                        'PUBLIC' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'public'
                                        ),
                                    ]
                                ); ?>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field class_for_hide_row">
                                <?= $form->field(
                                    $model,
                                    'trunk_username')->textInput(
                                    [
                                        'autocomplete' => 'off',
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_username'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                                <?= $form->field(
                                    $model,
                                    'trunk_password',
                                    [
                                        'template' => "{label}\n{input}\n<div class='mb-2'>{hint}</div>\n{error}",
                                    ])->passwordInput(
                                    [
                                        'autocomplete' => 'new-password',
                                        'id' => 'trunk_password',
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_password'))
                                    ]
                                ); ?>
                                <div class="passowrd-btns">
                                    <button type='button'
                                            class='btn waves-effect waves-light amber darken-4 togglePassword trunk_password'
                                            onclick="togglePassword('trunk_password');"><i class='material-icons'>visibility_off</i>
                                    </button>
                                </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'trunk_add_prefix')->textInput(
                                    [
                                        'onkeypress' => 'return isValidPrfix(event);',
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('trunk_add_prefix'))
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?php

                                echo $form->field(
                                    $model,
                                    'trunk_protocol',
                                    [
                                        'options' => [
                                            'class' => '',
                                        ],
                                    ])->dropDownList(
                                    [
                                        'TCP' => 'TCP',
                                        'UDP' => 'UDP',
                                        'TLS' => 'TLS',
                                    ]
                                ); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?php

                                echo $form->field(
                                    $model,
                                    'trunk_fax_support',
                                    [
                                        'options' => [
                                            'class' => '',
                                        ],
                                    ])->dropDownList(
                                    [
                                        '0' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'no'
                                        ),
                                        '1' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'yes'
                                        ),
                                    ]
                                ); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    if (!$model->isNewRecord) {
                                        echo $form->field(
                                            $model,
                                            'trunk_status',
                                            [
                                                'options' => [
                                                    'class' => '',
                                                ],
                                            ])->dropDownList(
                                            [
                                                'Y' => CarriertrunkModule::t(
                                                    'carriertrunk',
                                                    'active'
                                                ),
                                                'N' => CarriertrunkModule::t(
                                                    'carriertrunk',
                                                    'inactive'
                                                ),
                                            ]
                                        );
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'overwrite_config',
                                        ['options' => ['class' => '', 'id' => 'overwrite_config']])
                                        ->checkbox([
                                            'title' => CarriertrunkModule::t('carriertrunk', "overwrite_config_label"),
                                            'label' => '',
                                            'labelOptions' => ['style' => 'padding:5px;'],
                                            'id' => 'overwrite_config',
                                        ])->label(CarriertrunkModule::t('carriertrunk', "overwrite_config"));

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'trunk_channels', ['options' => ['class' => '']])
                                        ->textInput([
                                            'maxlength' => TRUE,
                                            'placeholder' => CarriertrunkModule::t('carriertrunk', "channels_placeholder"),
                                        ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'trunk_cps', ['options' => ['class' => '']])
                                        ->textInput([
                                            'maxlength' => TRUE,
                                            'placeholder' => CarriertrunkModule::t('carriertrunk', "cps_placeholder"),
                                        ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'caller_id', ['options' => ['class' => '']])
                                        ->textInput([
                                            'maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)",
                                            'placeholder' => CarriertrunkModule::t('carriertrunk', "caller_id"),
                                        ]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 mt-3 is_caller_id_override_div">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'is_caller_id_override',
                                        ['options' => ['class' => '', 'id' => '']])
                                        ->checkbox([
                                            'title' => CarriertrunkModule::t('carriertrunk', "is_caller_id_override"),
                                            'label' => CarriertrunkModule::t('carriertrunk', "is_caller_id_override"),
                                            'labelOptions' => ['style' => 'padding:5px;'],
                                            'id' => 'is_caller_id_override',
                                        ])->label(false);

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t7">
                        <h6 class="col s12trunk-heading">
                            <i class="material-icons align-middle">play_circle_outline</i>
                            <b class="trunk-title"><?= CarriertrunkModule::t(
                                    'carriertrunk',
                                    'codec_management'
                                ); ?></b>
                        </h6>
                    </div>
                    <div class="row mt-1">
                        <div class="col s12 m6 p-0">
                            <div class="row">
                                <div class="col s12">
                                        <span class="new badge gradient-45deg-light-blue-cyan"
                                              title="<?= CarriertrunkModule::t(
                                                  'carriertrunk',
                                                  'audio_codec_label'
                                              ); ?>" data-badge-caption="<?= CarriertrunkModule::t(
                                            'carriertrunk',
                                            'audio_codec'
                                        ); ?>">
                                        </span>
                                </div>
                            </div>
                            <div class="form-group field-groups-group_status required align-items-center">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-danger">
                                        <?php echo CarriertrunkModule::t(
                                            'carriertrunk',
                                            'available'
                                        ) ?></label>
                                    <select id="availableAudioCodecs"
                                            size="8"
                                            multiple="multiple"
                                            class="multiselect form-control"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {
                                            foreach (
                                                $availableAudioCodecs as
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $value . "'>" . $value
                                                    . "</option>";
                                            }
                                        } else {
                                            foreach (
                                                $availableAudioCodecUpdate
                                                as $key => $value
                                            ) {
                                                echo "<option value='"
                                                    . $value . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col s2 btn_div p-0 multiselect-btn-pad ">
                                <button type="button" id="btnRightAudio"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='avaliable'>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                                <button type="button" id="btnLeftAudio"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='assigned'>
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                            </div>
                            <div class="form-group field-groups-group_status required">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-success">
                                        <?php echo CarriertrunkModule::t(
                                            'carriertrunk',
                                            'assigned'
                                        ) ?>
                                    </label>
                                    <select id="assignedAudioCodecs"
                                            multiple size="7"
                                            class="multiselect form-control list"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {

                                        } else {
                                            foreach (
                                                $audioCodec as $key =>
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $value . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>

                                    </select>
                                    <?php

                                    echo $form->field($model, 'audioCodecs')
                                        ->hiddenInput(
                                            ['id' => 'selectedAudioCodecs']
                                        )
                                        ->label(FALSE)
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 p-0">
                            <div class="row">
                                <div class="col s12">
                                     <span class="new badge gradient-45deg-light-blue-cyan"
                                           title="<?= CarriertrunkModule::t(
                                               'carriertrunk',
                                               'video_codec_label'
                                           ); ?>" data-badge-caption="<?= CarriertrunkModule::t(
                                         'carriertrunk',
                                         'video_codec'
                                     ); ?>">
                                        </span>
                                </div>
                            </div>
                            <div class="form-group field-groups-group_status required align-items-center">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-danger">
                                        <?php echo CarriertrunkModule::t(
                                            'carriertrunk',
                                            'available'
                                        ) ?>
                                    </label>

                                    <select id="availableVideoCodecs"
                                            multiple size="7"
                                            class="multiselect form-control list"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {
                                            foreach (
                                                $availableVideoCodecs as
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $value
                                                    . "'>"
                                                    . $value
                                                    . "</option>";
                                            }
                                        } else {
                                            foreach (
                                                $availableVideoCodecUpdate
                                                as $key => $value
                                            ) {
                                                echo "<option value='"
                                                    . $value . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s2 btn_div p-0 multiselect-btn-pad ">
                                <button type="button" id="btnRightVideo"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='avaliable'>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                                <button type="button" id="btnLeftVideo"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='assigned'>
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                            </div>
                            <div class="form-group field-groups-group_status required">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-success">
                                        <?php echo CarriertrunkModule::t(
                                            'carriertrunk',
                                            'assigned'
                                        ) ?></label>
                                    <select id="assignedVideoCodecs"
                                            multiple size="7"
                                            class="multiselect form-control list"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {

                                        } else {
                                            foreach (
                                                $videoCodec as $key =>
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $value . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>
                                    </select>
                                    <?= $form->field(
                                        $model,
                                        'videoCodecs'
                                    )->hiddenInput(
                                        ['id' => 'selectedVideoCodecs']
                                    )
                                        ->label(FALSE)
                                    ?>
                                </div>
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
                            Yii::$app->session->get('tmaster_redirect_to'),
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

<!-- This block will be added to  on click of plus button -->
<div class="a_tk_ip_cont">
    <div class="a_tk_ip">
        <div class="form-group">
            <div class="row">

                <div class="tk_ip col-xs-12 col-md-4 col-sm-3 col-lg-4">
                    <?= Html::textInput(
                        'tii_ip[]',
                        '',
                        [
                            'id' => 'inbound_ip',
                            'placeholder' => CarriertrunkModule::t(
                                'carriertrunk',
                                'IP Address'
                            ),
                            'class' => 'form-control',
                            'onkeypress' => 'return isValidPrfix(event);',
                        ]
                    ); ?>
                </div>

                <!--End Here-->
                <div class="tk_port col-xs-12 col-md-3 col-sm-3 col-lg-3">
                    <?= Html::textInput(
                        'tii_port[]',
                        '',
                        [
                            'id' => 'inbound_port',
                            'maxlength' => 15,
                            'placeholder' => CarriertrunkModule::t(
                                'carriertrunk',
                                'Port'
                            ),
                            'class' => 'form-control',
                            'onkeypress' => 'return isNumberKey(event);',
                        ]
                    ); ?>
                </div>

                <div class="col-sm-3 col-lg-2 col-md-2">
                    <a href="javascript:void(0);"
                       class="btn btn-danger btn-sm fa fa-minus-circle fa-2x"
                       onclick="rem_tk_ip($(this))">
                        <i class="entypo-minus"></i>
                    </a>
                    <a href="javascript:void(0);"
                       class="btn btn-primary btn-sm add_ss fa fa-plus-circle"
                       onclick="add_tk_ip($(this))">
                        <i class="entypo-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", "#overwrite_config", function () {
        checkConfig();
    });

    function checkConfig() {
        if ($('input#overwrite_config').is(':checked')) {
            $('#trunkmaster-trunk_channels').removeAttr('readonly');
            $('#trunkmaster-trunk_cps').removeAttr('readonly');
        } else {
            $('#trunkmaster-trunk_channels').prop('readonly', 'readonly');
            $('#trunkmaster-trunk_cps').prop('readonly', 'readonly');
        }
    }

    checkConfig();

    $(document).ready(function () {
        $('.field-groups-group_status select').formSelect('destroy');
        $('.field-groups-group_status select').css('display', 'block');
        $('.field-groups-group_status select').css('height', '200px');
        $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');
        if($('#trunkmaster-caller_id').val() === '') {
            $('.is_caller_id_override_div').hide();
        }else{
            $('.is_caller_id_override_div').show();
        }

        if($('#trunkmaster-trunk_register').val() == '1'){
            $('.field-trunkmaster-trunk_username label').append(' <span style="color: red">*</span>');
            $('.field-trunk_password label').append(' <span style="color: red">*</span>');
        }else{
            var usernameLabel = $('.field-trunkmaster-trunk_username label');
            var usernameCurrentText = usernameLabel.text();
            var usernameNewText = usernameCurrentText.replace('*', '');
            usernameLabel.text(usernameNewText);

            var pwdLabel = $('.field-trunk_password label');
            var pwdCurrentText = pwdLabel.text();
            var pwdNewText = pwdCurrentText.replace('*', '');
            pwdLabel.text(pwdNewText);
        }
    });

    $('#trunkmaster-caller_id').on('keyup', function(event) {
        if(this.value.length === 0){
            $('.is_caller_id_override_div').hide();
        }else{
            $('.is_caller_id_override_div').show();
        }
    });

    function togglePassword(field_name) {
        var pwdType = $("#" + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("#" + field_name).attr("type", newType);
        var newEye = $('.'+ field_name +' .material-icons').text();
        $('.'+ field_name +' .material-icons').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
    }

    $(document).on('change', '#trunkmaster-trunk_register', function(){
        if($(this).val() == '1'){
            $('.field-trunkmaster-trunk_username label').append(' <span style="color: red">*</span>');
            $('.field-trunk_password label').append(' <span style="color: red">*</span>');
        }else{
            var usernameLabel = $('.field-trunkmaster-trunk_username label');
            var usernameCurrentText = usernameLabel.text();
            var usernameNewText = usernameCurrentText.replace('*', '');
            usernameLabel.text(usernameNewText);

            var pwdLabel = $('.field-trunk_password label');
            var pwdCurrentText = pwdLabel.text();
            var pwdNewText = pwdCurrentText.replace('*', '');
            pwdLabel.text(pwdNewText);
        }
    });

</script>
