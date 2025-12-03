<?php

use app\modules\ecosmob\extension\extensionModule;
use app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding */
/* @var $form yii\widgets\ActiveForm */
?>
<?php

$extensionlists=Extension::find()->where(['em_status'=>'1'])->all();
foreach ($extensionlists as &$ext) {
    $ext->em_extension_name=$ext->em_extension_name . '-' . $ext->em_extension_number;
}

$ext=ArrayHelper::map($extensionlists, 'em_extension_number', 'em_extension_name');

?>

<div class="row">
    <div class="col s12">
        <?php $form=ActiveForm::begin([
            'class'=>'row',
            'fieldConfig'=>[
                'options'=>[
                    'class'=>'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="extension-forwarding-form" id="extension-forwarding-form">
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field p-0">
                                <?= $form->field($call_setting_model, 'ecs_forwarding', ['options'=>['class'=>'', 'id'=>'ecs_forwarding_value']])
                                    ->dropDownList(['1'=> Yii::t('app','individual_forwarding'),
                                        '2'=>Yii::t('app','find_me_follow_me_forwarding')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ecs_forwarding_value'));
                                     ?>
                            </div>
                        </div>
                    </div>
                    <!-- INDIVIDUAL FORWARDING END  -->
                    <div id="individual_forwarding" style="display: none">
                        <!--  UNCONDITIONAL START -->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="col s12 input-field p-0">
                                    <?= $form->field($model, 'ef_unconditional_type', ['options'=>['class'=>'', 'id'=>'unconditional']])
                                        ->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])
                                        ->label(Yii::t('app', 'ef_unconditional_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="col s12 input-field p-0" id="uncoditional_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_unconditional_num', ['options'=>['class'=>'uncontional_num_select']])->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_unconditional_num').' <span style="color: red;">*</span>', ['class' => 'unconditional-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="col s12 input-field p-0 " id="uncoditional_external" style="display: none">
                                    <?= $form->field($model, 'ef_unconditional_num')->textInput(['maxlength'=>true, 'class'=>'uncontional_num_text'])->label(Yii::t('app', 'ef_unconditional_num').' <span style="color: red;">*</span>', ['class' => 'unconditional-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--  UNCONDITIONAL END -->

                        <!-- HOLIDAY LIST START-->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="col s12 input-field p-0">
                                    <?php
                                    $holidayList=ArrayHelper::map($holidaylist, 'hd_id', 'hd_holiday');
                                    echo $form->field($model, 'ef_holiday', ['options'=>['class'=>'']])->dropDownList
                                    ($holidayList, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'hd_holiday'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--HOLIDAY LIST END-->

                        <!--HOLIDAY TYPE START-->
                        <div class="row holiday-div">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?= $form->field($model, 'ef_holiday_type', ['options'=>['class'=>'', 'id'=>'holiday_type']])
                                        ->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])
                                        ->label(Yii::t('app', 'ef_holiday_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="holiday_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_holiday_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_holiday_num').' <span style="color: red;">*</span>', ['class' => 'holiday-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-field p-0 " id="holiday_external" style="display: none">
                                    <?= $form->field($model, 'ef_holiday_num')->textInput(['maxlength'=>true,
                                        'class'=>''])->label(Yii::t('app', 'ef_holiday_num').' <span style="color: red;">*</span>', ['class' => 'holiday-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--HOLIDAY TYPE END-->

                        <!--WEEK OFF LIST START-->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?php
                                    $weekOfList=ArrayHelper::map($weekOfList, 'wo_id', 'wo_day');
                                    echo $form->field($model, 'ef_weekoff', ['options'=>['class'=>'']])->dropDownList
                                    ($weekOfList, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_weekoff'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--WEEK OFF LIST END-->

                        <!--WEEK OFF TYPE START-->
                        <div class="row weekoff-div">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?= $form->field($model, 'ef_weekoff_type', ['options'=>['class'=>'', 'id'=>'week_off_type']])
                                        ->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])
                                        ->label(Yii::t('app', 'ef_weekoff_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="week_off_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_weekoff_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_weekoff_num').' <span style="color: red;">*</span>', ['class' => 'weekoff-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-field p-0 " id="week_off_external" style="display: none">
                                    <?= $form->field($model, 'ef_weekoff_num')->textInput(['maxlength'=>true,
                                        'class'=>'uncontional_num_text'])->label(Yii::t('app', 'ef_weekoff_num').' <span style="color: red;">*</span>', ['class' => 'weekoff-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--WEEK OFF TYPE END-->

                        <!--SHIFT LIST START-->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?php
                                    $shiftList=ArrayHelper::map($shiftList, 'sft_id', 'sft_name');
                                    echo $form->field($model, 'ef_shift', ['options'=>['class'=>'']])->dropDownList
                                    ($shiftList, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_shift'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--SHIFT LIST END------->

                        <!--SHIFT TYPE START-->
                        <div class="row shift-div">
                            <div class="col s12 m6 l4">
                                <div class="col s12 input-field p-0">
                                    <?= $form->field($model, 'ef_shift_type', ['options'=>['class'=>'', 'id'=>'shift_type']])
                                        ->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])
                                        ->label(Yii::t('app', 'ef_shift_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="shift_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_shift_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_shift_num').' <span style="color: red;">*</span>', ['class' => 'shift-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-field p-0 " id="shift_external" style="display: none">
                                    <?= $form->field($model, 'ef_shift_num')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ef_shift_num').' <span style="color: red;">*</span>', ['class' => 'shift-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--SHIFT TYPE END-->

                        <!--UNIVERSAL START-->
                        <!--<div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?php /*= $form->field($model, 'ef_universal_type', ['options'=>['class'=>'universal', 'id'=>'universal']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app', 'select')])->label(Yii::t('app', 'ef_universal_type')); */?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="universal_internal" style="display: none;">
                                    <div class="select-wrapper">
                                        <?php
/*                                        echo $form->field($model, 'ef_universal_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app', 'select')])->label(Yii::t('app', 'ef_universal_num'), ['class' => 'universal-int']);
                                        */?>
                                    </div>
                                </div>
                                <div class="input-field p-0" id="universal_external" style="display: none">
                                    <?php /*= $form->field($model, 'ef_universal_num')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ef_universal_num'), ['class' => 'universal-ext']); */?>
                                </div>
                            </div>
                        </div>-->
                        <!--UNIVERSAL END-->

                        <!--NO ANSWER START-->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?= $form->field($model, 'ef_no_answer_type', ['options'=>['class'=>'no_answer', 'id'=>'no_answer']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_no_answer_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="no_answer_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_no_answer_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_no_answer_num').' <span style="color: red;">*</span>', ['class' => 'no-answer-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-field p-0" id="no_answer_external" style="display: none">
                                    <?= $form->field($model, 'ef_no_answer_num')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ef_no_answer_num').' <span style="color: red;">*</span>', ['class' => 'no-answer-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--NO ANSWER END-->

                        <!-- FOR BUSY -->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="col s12 p-0">
                                    <?= $form->field($model, 'ef_busy_type', ['options'=>['class'=>'busy_for', 'id'=>'busy_for']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_busy_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="col s12  p-0" id="busy_for_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($model, 'ef_busy_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_busy_num').' <span style="color: red;">*</span>', ['class' => 'busy-num-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="col s12 p-0" id="busy_for_external" style="display: none">
                                    <?= $form->field($model, 'ef_busy_num')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ef_busy_num').' <span style="color: red;">*</span>', ['class' => 'busy-num-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!--FOR BUSY END-->

                        <!-- UNAVAILABLE START -->
                        <!--<div class="row">
                            <div class="col s12 m6 l4">
                                <div class="col s12 p-0">
                                    <?php /*= $form->field($model, 'ef_unavailable_type', ['options'=>['class'=>'unavailable', 'id'=>'unavailable']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_unavailable_type')); */?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="col s12 p-0" id="unavailable_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
/*                                        echo $form->field($model, 'ef_unavailable_num', ['options'=>['class'=>'']])
                                            ->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ef_unavailable_num').' <span style="color: red;">*</span>', ['class' => 'unavailable-int']);
                                        */?>
                                    </div>
                                </div>
                                <div class="col s12 p-0" id="unavailable_external" style="display: none">
                                    <?php /*= $form->field($model, 'ef_unavailable_num')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ef_unavailable_num').' <span style="color: red;">*</span>', ['class' => 'unavailable-ext']); */?>
                                </div>
                            </div>
                        </div>-->
                        <!--UNAVAILABLE END-->
                    </div>
                    <!-- INDIVIDUAL FORWARDING END  -->

                    <!-- FIND ME FOLLOW ME FORWARDING START -->
                    <!-- FOR FF TYPE 1 START -->
                    <div id="findme_followme_forwarding" style="display: none">
                        <div class="row ">
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0">
                                    <?= $form->field($findme_followme_model, 'ff_1_type', ['options'=>['class'=>'', 'id'=>'ff_1_type']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_1_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field p-0" id="findme_followme_1_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($findme_followme_model, 'ff_1_extension', ['options'=>['class'=>'']])->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_1_extension').' <span style="color: red;">*</span>', ['class' => 'ff1-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-field p-0" id="findme_followme_1_external" style="display: none">
                                    <?= $form->field($findme_followme_model, 'ff_1_extension')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ff_1_extension').' <span style="color: red;">*</span>', ['class' => 'ff1-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!-- FOR FF TYPE 1 END -->

                        <!-- FOR FF TYPE 2 START  -->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class=" col s12 p-0">
                                    <?= $form->field($findme_followme_model, 'ff_2_type', ['options'=>['class'=>'', 'id'=>'ff_2_type']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_2_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class=" col s12 p-0" id="findme_followme_2_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($findme_followme_model, 'ff_2_extension', ['options'=>['class'=>'']])->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_2_extension').' <span style="color: red;">*</span>', ['class' => 'ff2-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="col s12 p-0" id="findme_followme_2_external" style="display: none">
                                    <?= $form->field($findme_followme_model, 'ff_2_extension')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ff_2_extension').' <span style="color: red;">*</span>', ['class' => 'ff2-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!-- FOR FF TYPE 2 END   -->

                        <!-- FOR FF TYPE 3 START  -->
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="col s12 p-0">
                                    <?= $form->field($findme_followme_model, 'ff_3_type', ['options'=>['class'=>'', 'id'=>'ff_3_type']])->dropDownList(['INTERNAL'=>Yii::t('app','internal'), 'EXTERNAL'=>Yii::t('app','external'), 'VOICEMAIL'=>Yii::t('app','voice_mail')], ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_3_type')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="col s12 p-0" id="findme_followme_3_internal" style="display: none">
                                    <div class="select-wrapper">
                                        <?php
                                        echo $form->field($findme_followme_model, 'ff_3_extension', ['options'=>['class'=>'']])->dropDownList($ext, ['prompt'=>Yii::t('app','select')])->label(Yii::t('app', 'ff_3_extension').' <span style="color: red;">*</span>', ['class' => 'ff3-int']);
                                        ?>
                                    </div>
                                </div>
                                <div class="col s12 p-0" id="findme_followme_3_external" style="display: none">
                                    <?= $form->field($findme_followme_model, 'ff_3_extension')->textInput(['maxlength'=>true, 'class'=>''])->label(Yii::t('app', 'ff_3_extension').' <span style="color: red;">*</span>', ['class' => 'ff3-ext']); ?>
                                </div>
                            </div>
                        </div>
                        <!-- FOR FF TYPE 3 END   -->
                    </div>

                    <!-- FIND ME FOLLOW ME FORWARDING END -->


                    <!--RING  TIME OUT & CALL TIME OUT START -->
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <?= $form->field($call_setting_model, 'ecs_ring_timeout')
                                ->textInput(['maxlength'=>true, 'placeholder' => Yii::t('app', 'extension_ring_timeout')
                                ])
                                ->label(Yii::t('app', 'extension_ring_timeout')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($call_setting_model, 'ecs_call_timeout')
                                    ->textInput(['maxlength'=>true, 'placeholder' => Yii::t('app', 'extension_call_timeout')
                                    ])
                                    ->label(Yii::t('app', 'extension_call_timeout')); ?>
                            </div>
                        </div>
                    </div>
                    <!--RING  TIME OUT & CALL TIME OUT END -->

                    <!--  ACTIVE DEACTIVE STATUS FOR VOICEMAIL START  -->
                    <div class="row">
                        <div class="col s12">
                            <div class="d-flex align-items-center">
                                <label class='mr-3'> <?= Yii::t('app','voice_mail') ?>: </label>
                                <div class="switch">
                                    <label><?=Yii::t('app','off')?>
                                        <?= Html::activeCheckbox($call_setting_model, 'ecs_voicemail', ['uncheck'=>0, 'label'=>FALSE]) ?>
                                        <span class="lever"></span>
                                        <?=Yii::t('app','on')?></label>
                                </div>
                            </div>
                        </div>
                        <!--  ACTIVE DEACTIVE STATUS FOR VOICEMAIL END  -->

                        <!-- ACTIVE DEACTIVE STATUS FOR BLACK LIST START -->
                       <!-- <div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','black_list') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_blacklist', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR BLACK LIST END -->
                    </div>

                    <!-- ACTIVE DEACTIVE STATUS FOR ACCEPT BLOCKED CALLER START -->
                    <div class="row">
                        <!--<div class="col s12 m6 l4  p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','accept_blocked_caller_id') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_accept_blocked_caller_id', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR ACCEPT BLOCKED CALLER END -->

                        <!-- ACTIVE DEACTIVE STATUS FOR REDIAl START -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','call_redial') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_call_redial', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <!-- ACTIVE DEACTIVE STATUS FOR REDIAl END -->

                    <!--  ACTIVE DEACTIVE STATUS FOR BARGEIN START  -->
                    <div class="row">
                       <!-- <div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','bargein') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_bargein', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR BARGEIN END -->

                        <!--  ACTIVE DEACTIVE STATUS FOR BUSY CALL START  -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','busy_call_back') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*= Yii::t('app','off') */?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_busy_call_back', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!--  ACTIVE DEACTIVE STATUS FOR BUSY CALL END  -->
                    </div>

                    <!--  ACTIVE DEACTIVE STATUS FOR PARk START  -->
                    <div class="row">
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','park') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_park', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR PARK END -->

                        <!-- ACTIVE DEACTIVE STATUS FOR DND START -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','do_not_disturb') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_do_not_disturb', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR DND END -->
                    </div>


                    <div class="row">
                        <!--  ACTIVE DEACTIVE STATUS FOR CALLER ID BLOCK START  -->
                       <!-- <div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','caller_id_block') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_caller_id_block', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!--  ACTIVE DEACTIVE STATUS FOR CALLER ID BLOCK -->


                        <!--  ACTIVE DEACTIVE STATUS FOR WHITELIST START  -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','white_list') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_whitelist', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR WHITE LIST END -->
                    </div>

                    <div class="row">
                        <!--  ACTIVE DEACTIVE STATUS FOR RECORDING START  -->
                       <!-- <div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','call_recording') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_call_recording', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!--  ACTIVE DEACTIVE STATUS FOR RECORDING END  -->

                        <!--  ACTIVE DEACTIVE STATUS FOR RETURN START  -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','call_return') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_call_return', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!-- ACTIVE DEACTIVE STATUS FOR CALL RETURN END -->

                        <!--  ACTIVE DEACTIVE STATUS FOR CALL TRANSFER START  -->
                       <!-- <div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app','transfer') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*=Yii::t('app','off')*/?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_transfer', ['uncheck'=>0, 'label'=>FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*=Yii::t('app','on')*/?></label>
                                </div>
                            </div>
                        </div>-->
                        <!--  ACTIVE DEACTIVE STATUS FOR CALL TRANSFER END  -->
                    </div>
                    <div class="row">
                        <!--  ACTIVE DEACTIVE STATUS FOR CALL WAITING START  -->
                        <!--<div class="col s12 m6 l4 p-0 mt-2">
                            <div class="col s12 m6 l4 ">
                                <p class=h4> <?php /*= Yii::t('app', 'call_waiting') */?>: </p>
                            </div>
                            <div class="col s12 m6 l4 ">
                                <div class="switch">
                                    <label><?php /*= Yii::t('app', 'off') */?>
                                        <?php /*= Html::activeCheckbox($call_setting_model, 'ecs_call_waiting', ['uncheck' => 0, 'label' => FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*= Yii::t('app', 'on') */?></label>
                                </div>
                            </div>
                        </div>-->
                        <!--  ACTIVE DEACTIVE STATUS FOR CALL WAITING START  -->
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
            <?= Html::a(Yii::t('app', 'cancel'), ['/extensionforwarding/extension-forwarding/forwading', 'page'=>Yii::$app->session->get('page')],
                    ['class'=>'btn waves-effect waves-light bg-gray-200 ']) ?>
                <?= Html::submitButton($model->isNewRecord ? ExtensionForwardingModule::t('extensionforwarding', 'Create') : ExtensionForwardingModule::t('extensionforwarding', 'Update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
    <script type="text/javascript">
        /*FOR UNCONDITIONAL HIDE SHOW  START*/
        var firstload = 1;
        $(document).ready(function () {
            /*$('#unconditional').trigger('change');
            $('#holiday_type').trigger('change');
            $('#week_off_type').trigger('change');
            $('#shift_type').trigger('change');
            $('#universal').trigger('change');
            $('#no_answer').trigger('change');
            $('#busy_for').trigger('change');
            $('#unavailable').trigger('change');*/
            $('#ecs_forwarding_value').trigger('change');
            /*$('#ff_1_type').trigger('change');
            $('#ff_2_type').trigger('change');
            $('#ff_3_type').trigger('change');*/
        });

        if($('#extensionforwarding-ef_holiday').val() == ''){
            $('.holiday-div').hide();
        }else{
            $('.holiday-div').show();
        }

        if($('#extensionforwarding-ef_weekoff').val() == ''){
            $('.weekoff-div').hide();
        }else{
            $('.weekoff-div').show();
        }

        if($('#extensionforwarding-ef_shift').val() == ''){
            $('.shift-div').hide();
        }else{
            $('.shift-div').show();
        }

        $(document).on('change', '#extensionforwarding-ef_holiday', function(){
           if($(this).val() == ''){
               $('.holiday-div').hide();
           }else{
               $('.holiday-div').show();
           }
        });

        $(document).on('change', '#extensionforwarding-ef_weekoff', function(){
            if($(this).val() == ''){
                $('.weekoff-div').hide();
            }else{
                $('.weekoff-div').show();
            }
        });

        $(document).on('change', '#extensionforwarding-ef_shift', function(){
            if($(this).val() == ''){
                $('.shift-div').hide();
            }else{
                $('.shift-div').show();
            }
        });

        /* For ECS FORWARDING HIDE SHOW DIV START */
        $(document).on('change', '#ecs_forwarding_value', function () {
            $('#individual_forwarding').find('select').attr('disabled', 'disabled');
            $('#individual_forwarding').find('input').attr('disabled', 'disabled');

            $('#findme_followme_forwarding').find('select').attr('disabled', 'disabled');
            $('#findme_followme_forwarding').find('input').attr('disabled', 'disabled');

            if ($(this).find(':selected').val() == '1') {
                $("#individual_forwarding").show();
                $("#findme_followme_forwarding").hide();
                $('#individual_forwarding').find('select').removeAttr('disabled');
                $('#individual_forwarding').find('input').removeAttr('disabled');
                $('#unconditional').trigger('change');
                $('#holiday_type').trigger('change');
                $('#week_off_type').trigger('change');
                $('#shift_type').trigger('change');
                $('#universal').trigger('change');
                $('#no_answer').trigger('change');
                $('#busy_for').trigger('change');
                $('#unavailable').trigger('change');
            } else if ($(this).find(':selected').val() == '2') {
                $("#findme_followme_forwarding").show();
                $("#individual_forwarding").hide();
                $('#findme_followme_forwarding').find('select').removeAttr('disabled');
                $('#findme_followme_forwarding').find('input').removeAttr('disabled');
                $('#ff_1_type').trigger('change');
                $('#ff_2_type').trigger('change');
                $('#ff_3_type').trigger('change');
            } else {
                $("#findme_followme_forwarding").hide();
                $("#individual_forwarding").hide();

            }
            firstload = 0;

        });
        /* For ECS FORWARDING HIDE SHOW DIV END */

        /* UNCONTIONAL TYPE HIDE SHOW START */

        $(document).on('change', '#unconditional', function () {
            if (!firstload) {
                $('#uncoditional_external').find('input').val('');
                $("#uncoditional_external").parent().find('.help-block').html('');
                // $('#uncoditional_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#uncoditional_internal").show();

                $('#uncoditional_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#uncoditional_external").hide();
                $('#uncoditional_external').find('input').attr('disabled', 'disabled');

                $(".unconditional-int").text('Extension Number');
                $(".unconditional-int").append(' <span style="color: red;">*</span>');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {


                // input box will appear and remove disable
                $("#uncoditional_external").show();
                $('#uncoditional_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#uncoditional_internal").hide();
                $('#uncoditional_internal').find('select').attr('disabled', 'disabled');

                $(".unconditional-ext").text('External Number');
                $(".unconditional-ext").append(' <span style="color: red;">*</span>');

            } else {
                $("#uncoditional_external").hide();
                $("#uncoditional_internal").hide();
                $('#uncoditional_internal').find('select').attr('disabled', 'disabled');
                $('#uncoditional_external').find('input').attr('disabled', 'disabled');

            }
        });
        /*UNCONDTIONAL TYPE HIDE SHOW END*/

        /*FOR HOLIDAY TYPE HIDE SHOW*/

        $(document).on('change', '#holiday_type', function () {
            if (!firstload) {
                $('#holiday_external').find('input').val('');
                //$('#holiday_external').parent().find('.help-block').html('');
                // $('#holiday_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#holiday_internal").show();
                $('#holiday_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#holiday_external").hide();
                $('#holiday_external').find('input').attr('disabled', 'disabled');

                $(".holiday-int").text('Extension Number');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#holiday_external").show();
                $('#holiday_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#holiday_internal").hide();
                $('#holiday_internal').find('select').attr('disabled', 'disabled');

                $(".holiday-ext").text('External Number');

            } else {
                $("#holiday_external").hide();
                $("#holiday_internal").hide();
                $('#holiday_internal').find('select').attr('disabled', 'disabled');
                $('#holiday_external').find('input').attr('disabled', 'disabled');

            }
        });

        /*FOR WEEK OFF TYPE HIDE SHOW START*/
        $(document).on('change', '#week_off_type', function () {
            if (!firstload) {
                $('#week_off_external').find('input').val('');
                $('#week_off_external').parent().find('.help-block').html('');
                // $('#week_off_internal select').val('').select2('destroy').select2();
            }
            // alert($(this).find(':selected').val());
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#week_off_internal").show();
                $('#week_off_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#week_off_external").hide();
                $('#week_off_external').find('input').attr('disabled', 'disabled');

                $(".weekoff-int").text('Extension Number');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#week_off_external").show();
                $('#week_off_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#week_off_internal").hide();
                $('#week_off_internal').find('select').attr('disabled', 'disabled');

                $(".weekoff-ext").text('External Number');

            } else {
                $("#week_off_external").hide();
                $("#week_off_internal").hide();
                $('#week_off_internal').find('select').attr('disabled', 'disabled');
                $('#week_off_external').find('input').attr('disabled', 'disabled');
            }
        });
        /*END WEEK OFF TYPE HIDE SHOW*/


        /*SHIFT TYPE HIDE SHOW START*/
        $(document).on('change', '#shift_type', function () {
            if (!firstload) {
                $('#shift_external').find('input').val('');
                $('#shift_external').parent().find('.help-block').html('');
                // $('#shift_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#shift_internal").show();
                $('#shift_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#shift_external").hide();
                $('#shift_external').find('input').attr('disabled', 'disabled');

                $(".shift-int").text('Extension Number');
                $(".shift-int").append(' <span style="color: red;">*</span>');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#shift_external").show();
                $('#shift_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#shift_internal").hide();
                $('#shift_internal').find('select').attr('disabled', 'disabled');

                $(".shift-ext").text('External Number');
                $(".shift-ext").append(' <span style="color: red;">*</span>');

            } else {
                $("#shift_external").hide();
                $("#shift_internal").hide();
                $('#shift_internal').find('select').attr('disabled', 'disabled');
                $('#shift_external').find('input').attr('disabled', 'disabled');

            }
        });
        /*SHIFT TYPE HIDE SHOW END*/


        /*FOR UNIVERSAL HIDE SHOW START*/

        $(document).on('change', '#universal', function () {
            if (!firstload) {
                $('#universal_external').find('input').val('');
                $('#universal_external').parent().find('.help-block').html('');
                // $('#universal_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#universal_internal").show();
                $('#universal_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#universal_external").hide();
                $('#universal_external').find('input').attr('disabled', 'disabled');

                $(".universal-int").text('Extension Number');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#universal_external").show();
                $('#universal_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#universal_internal").hide();
                $('#universal_internal').find('select').attr('disabled', 'disabled');

                $(".universal-ext").text('External Number');
            } else {


                $("#universal_external").hide();
                $("#universal_internal").hide();
                $('#universal_internal').find('select').attr('disabled', 'disabled');
                $('#universal_external').find('input').attr('disabled', 'disabled');

            }
        });
        /*FOR UNIVERSAL HIDE SHOW END*/

        /*FOR NO ANSWER HIDE SHOW START*/
        $(document).on('change', '#no_answer', function () {
            if (!firstload) {
                $('#no_answer_external').find('input').val('');
                $('#no_answer_external').parent().find('.help-block').html('');
                // $('#no_answer_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#no_answer_internal").show();
                $('#no_answer_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#no_answer_external").hide();
                $('#no_answer_external').find('input').attr('disabled', 'disabled');

                $(".no-answer-int").text('Extension Number');
                $(".no-answer-int").append(' <span style="color: red;">*</span>');
            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#no_answer_external").show();
                $('#no_answer_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#no_answer_internal").hide();
                $('#no_answer_internal').find('select').attr('disabled', 'disabled');

                $(".no-answer-ext").text('External Number');
                $(".no-answer-ext").append(' <span style="color: red;">*</span>');
            } else {
                $("#no_answer_external").hide();
                $("#no_answer_internal").hide();
                $('#no_answer_internal').find('select').attr('disabled', 'disabled');
                $('#no_answer_external').find('input').attr('disabled', 'disabled');
            }
        });
        /*FOR NO ANSWER HIDE SHOW END*/

        /*FOR BUSY HIDE SHOW START*/


        $(document).on('change', '#busy_for', function () {
            if (!firstload) {
                $('#busy_for_external').find('input').val('');
                $('#busy_for_external').parent().find('.help-block').html('');
                // $('#busy_for_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#busy_for_internal").show();
                $('#busy_for_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#busy_for_external").hide();
                $('#busy_for_external').find('input').attr('disabled', 'disabled');

                $(".busy-num-int").text('Extension Number');
                $(".busy-num-int").append(' <span style="color: red;">*</span>');

            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#busy_for_external").show();
                $('#busy_for_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#busy_for_internal").hide();
                $('#busy_for_internal').find('select').attr('disabled', 'disabled');

                $(".busy-num-ext").text('External Number');
                $(".busy-num-ext").append(' <span style="color: red;">*</span>');
            } else {
                $("#busy_for_external").hide();
                $("#busy_for_internal").hide();
                $('#busy_for_internal').find('select').attr('disabled', 'disabled');
                $('#busy_for_external').find('input').attr('disabled', 'disabled');
            }
        });
        /*FOR BUSY HIDE SHOW END*/

        /*FOR UNAVAILABLE HIDE SHOW START*/
        $(document).on('change', '#unavailable', function () {
            if (!firstload) {
                $('#unavailable_external').find('input').val('');
                $('#unavailable_external').parent().find('.help-block').html('');
                // $('#unavailable_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#unavailable_internal").show();
                $('#unavailable_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#unavailable_external").hide();
                $('#unavailable_external').find('input').attr('disabled', 'disabled');

                $(".unavailable-int").text('Extension Number');
            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#unavailable_external").show();
                $('#unavailable_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#unavailable_internal").hide();
                $('#unavailable_internal').find('select').attr('disabled', 'disabled');

                $(".unavailable-ext").text('External Number');
            } else {
                $("#unavailable_external").hide();
                $("#unavailable_internal").hide();
                $('#unavailable_internal').find('select').attr('disabled', 'disabled');
                $('#unavailable_external').find('input').attr('disabled', 'disabled');

            }
        });
        /*FOR UNAVAILABLE HIDE SHOW END*/


        /* FIND ME FOLLOW ME 1 TYPE START */

        $(document).on('change', '#ff_1_type', function () {
            /*alert($(this).find(':selected').val());*/
            if (!firstload) {
                $('#findme_followme_1_external').find('input').val('');
                $('#findme_followme_1_external').parent().find('.help-block').html('');
                // $('#findme_followme_1_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#findme_followme_1_internal").show();
                $('#findme_followme_1_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#findme_followme_1_external").hide();
                $('#findme_followme_1_external').find('input').attr('disabled', 'disabled');

                $(".ff1-int").text('Extension Number');
                $(".ff1-int").append(' <span style="color: red;">*</span>');
            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#findme_followme_1_external").show();
                $('#findme_followme_1_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#findme_followme_1_internal").hide();
                $('#findme_followme_1_internal').find('select').attr('disabled', 'disabled');

                $(".ff1-ext").text('External Number');
                $(".ff1-ext").append(' <span style="color: red;">*</span>');
            } else {


                $("#findme_followme_1_external").hide();
                $("#findme_followme_1_internal").hide();
                $('#findme_followme_1_internal').find('select').attr('disabled', 'disabled');
                $('#findme_followme_1_external').find('input').attr('disabled', 'disabled');

            }
        });
        /* FIND ME FOLLOW ME 1 TYPE END */

        /* FIND ME FOLLOW ME 2 TYPE START */
        $(document).on('change', '#ff_2_type', function () {
            if (!firstload) {
                $('#findme_followme_2_external').find('input').val('');
                $('#findme_followme_2_external').parent().find('.help-block').html('');
                // $('#findme_followme_2_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#findme_followme_2_internal").show();
                $('#findme_followme_2_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#findme_followme_2_external").hide();
                $('#findme_followme_2_external').find('input').attr('disabled', 'disabled');

                $(".ff2-int").text('Extension Number');
                $(".ff2-int").append(' <span style="color: red;">*</span>');
            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#findme_followme_2_external").show();
                $('#findme_followme_2_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#findme_followme_2_internal").hide();
                $('#findme_followme_2_internal').find('select').attr('disabled', 'disabled');

                $(".ff2-ext").text('External Number');
                $(".ff2-ext").append(' <span style="color: red;">*</span>');
            } else {


                $("#findme_followme_2_external").hide();
                $("#findme_followme_2_internal").hide();
                $('#findme_followme_2_internal').find('select').attr('disabled', 'disabled');
                $('#findme_followme_2_external').find('input').attr('disabled', 'disabled');

            }
        });
        /* FIND ME FOLLOW ME 2 TYPE END */

        /* FIND ME FOLLOW ME 3 TYPE START  */
        $(document).on('change', '#ff_3_type', function () {
            if (!firstload) {
                $('#findme_followme_3_external').find('input').val('');
                $('#findme_followme_3_external').parent().find('.help-block').html('');
                // $('#findme_followme_3_internal select').val('').select2('destroy').select2();
            }
            if ($(this).find(':selected').val() == 'INTERNAL') {

                // select box will appear and remove disable
                $("#findme_followme_3_internal").show();
                $('#findme_followme_3_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable
                $("#findme_followme_3_external").hide();
                $('#findme_followme_3_external').find('input').attr('disabled', 'disabled');

                $(".ff3-int").text('Extension Number');
                $(".ff3-int").append(' <span style="color: red;">*</span>');
            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#findme_followme_3_external").show();
                $('#findme_followme_3_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#findme_followme_3_internal").hide();
                $('#findme_followme_3_internal').find('select').attr('disabled', 'disabled');

                $(".ff3-ext").text('External Number');
                $(".ff3-ext").append(' <span style="color: red;">*</span>');
            } else {


                $("#findme_followme_3_external").hide();
                $("#findme_followme_3_internal").hide();
                $('#findme_followme_3_internal').find('select').attr('disabled', 'disabled');
                $('#findme_followme_3_external').find('input').attr('disabled', 'disabled');

            }
        });
        /* FIND ME FOLLOW ME 3 TYPE END */

    </script>

    <!-- <style type="text/css">
        .mrg-btn {
            margin-bottom: 1em;
        }

        .input-text-right {
            right: 1rem !important;
        }
    </style> -->



