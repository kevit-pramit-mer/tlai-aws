<?php

use app\modules\ecosmob\autoattendant\assets\AutoAttendantAsset;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMaster */
/* @var $audioList app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $autoAttendantKeys app\modules\ecosmob\autoattendant\models\AutoAttendantKeys */
/* @var $detailModelError app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $allAutoAttendantDetails app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $allData app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $autoDetail app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $jsTreeData app\modules\ecosmob\autoattendant\controllers\AutoattendantController */

AutoAttendantAsset::register($this);
$id = Yii::$app->request->get('id');

$linkModel = AutoAttendantMaster::findOne(['aam_id' => $id]);

$linkId = $linkModel->aam_mapped_id;

?>
<div class="auto-attendant-setting-form"
     id="auto-attendant-setting-form">

    <?php $form = ActiveForm::begin([
        'id' => 'auto-attendant-master-setting-form',
    ]); ?>
    <br>

    <div class="col-sm-4">
        <div class="content">
            <div data-plugin="custom-scroll" data-height="auto" data-min="">
                <?php
                try {
                    echo $this->render('_treeview', ['jsTreeData' => $jsTreeData, 'id' => $id]);
                } catch (InvalidCallException $e) {
                } catch (ViewNotFoundException $e) {
                }
                ?>
                <br><br>
            </div>
        </div>
        <div>
            <?php
            if ($id != $linkId) {
                ?>

                <a href="<?= Yii::$app->urlManager->createUrl(['/autoattendant/autoattendant/settings?id=' . $linkId]); ?>">
                    <span class="btn btn-primary btn-round-left btn-sm mt-1 mb-2 hvr-icon-back"><?= AutoAttendantModule::t('autoattendant',
                            'return_to_parent') ?>
                    </span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>


    <div class="col-sm-8 pul">
        <div class="content">
            <div class="row">
                <div class="form-group">

                    <?= $form->field($model, 'aam_name', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput([
                        'maxlength' => 20,
                        'Disabled' => TRUE,
                        'placeholder' => AutoAttendantModule::t('autoattendant', 'aam_name'),
                    ])->label(AutoAttendantModule::t('autoattendant',
                            'aam_name')
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                        . AutoAttendantModule::t('autoattendant',
                            'aam_name_label') . '" ></i></span>') ?>

                    <?= $form->field($model,
                        'aam_extension',
                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput([
                        'maxlength' => 15,
                        'readonly' => $model->isNewRecord ? FALSE : TRUE,
                        'placeholder' => AutoAttendantModule::t('autoattendant', 'aam_extension'),
                        'onkeypress' => 'return isNumberOnly(event);',
                        'title' => AutoAttendantModule::t('autoattendant', 'cant_change_extension_number'),
                    ])->label(AutoAttendantModule::t('autoattendant',
                            'aam_extension')
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                        . AutoAttendantModule::t('autoattendant',
                            'aam_extension_label') . '" ></i></span>') ?>


                </div>
            </div>
            <?php if ($model->isNewRecord) { ?>
                <div class="raw">
                    <div class="col-xs-12 col-md-6">

                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div style="color:#55595c"><i>
                                * <?= AutoAttendantModule::t('autoattendant', 'cant_change_extension_number'); ?></i>
                        </div>
                    </div>
                </div>
            <?php } ?>


            <div class="row">
                <div class="form-group">
                    <?= $form->field($model,
                        'aam_timeout',
                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput([
                        'maxlength' => 5,
                        'placeholder' => AutoAttendantModule::t('autoattendant', 'timeout_seconds'),
                        'Disabled' => TRUE,
                    ])->label(AutoAttendantModule::t('autoattendant',
                            'aam_timeout')
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                        . AutoAttendantModule::t('autoattendant',
                            'aam_timeout_label') . '" ></i></span>') ?>

                    <?= $form->field($model,
                        'aam_inter_digit_timeout',
                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput([
                        'maxlength' => 5,
                        'placeholder' => AutoAttendantModule::t('autoattendant', 'inter_digit_timeout_seconds'),
                        'Disabled' => TRUE,
                    ])->label(AutoAttendantModule::t('autoattendant',
                            'aam_inter_digit_timeout')
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                        . AutoAttendantModule::t('autoattendant',
                            'aam_inter_digit_timeout_label') . '" ></i></span>') ?>
                </div>
            </div>

            <div class="row">
                <div class="form-group">

                    <?php if (!$model->isNewRecord) {
                        echo $form->field($model,
                            'aam_status',
                            ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList([
                            'Y' => 'Active',
                            'N' => 'Inactive',
                        ],
                            [
                                'Disabled' => TRUE,
                            ])->label(AutoAttendantModule::t('autoattendant',
                                'aam_status')
                            . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                            . AutoAttendantModule::t('autoattendant',
                                'aam_status_label') . '" ></i></span>');
                    }
                    ?>

                    <label class="ml-1"><?= AutoAttendantModule::t('autoattendant',
                            'aam_direct_ext_call')
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover" data-placement="top" data-trigger = "hover" data-content = "'
                        . AutoAttendantModule::t('autoattendant',
                            'aam_direct_ext_call_label') . '" ></i></span>' ?></label>
                    <div class="checkbox">
                        <?= $form->field(
                            $model,
                            'aam_direct_ext_call',
                            [
                                'template' => '<div class="checkbox-squared">{input}<label for="checkbox-squared1" class=""></label><span class=""></span></div>',
                                'options' => ['class' => 'col-sm-6'],
                            ]
                        )->checkbox(
                            [
                                'label' => FALSE,
                                'id' => 'checkbox-squared1',
                                'Disabled' => TRUE,
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
            <div class="hseparator"></div>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo AutoAttendantModule::t('autoattendant', 'digit'); ?></th>
                    <th><?php echo AutoAttendantModule::t('autoattendant', 'action'); ?></th>
                    <th class="text-center"><?php echo AutoAttendantModule::t('autoattendant', 'value'); ?></th>
                </tr>
                </thead>
            </table>

            <?php
            for ($i = 0; $i < 10; $i++) {

                ?>

                <div class="row">
                    <div class="col-xs-4 col-md-2">

                        <?php
                        echo Html::textInput('digit[]',
                            $i,
                            [
                                'class' => 'form-control',
                                'readonly' => TRUE,
                                'id' => 'digits_' . $i,
                            ]);
                        ?>
                    </div>

                    <div class="col-xs-4 col-md-7">

                        <?php
                        if ($detailModelError || $model->hasErrors()) {
                            $actions = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['actions'] : '';
                            $audio = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['audio'] : '';
                            $extension = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['extension'] : '';
                            $vextension = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['vextension'] : '';
                            $outbound_extension = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['outbound_extension']
                                : '';
                            $submenu = !empty($allAutoAttendantDetails[$i]) ? $allAutoAttendantDetails[$i]['submenu'] : '';
                        } else {
                            $actions = !empty($autoDetail[$i]) ? $autoDetail[$i] : '';
                            $audio = '';
                            $extension = '';
                            $vextension = '';
                            $outbound_extension = '';
                            $submenu = '';
                            if ($actions == 'Playfile') {
                                $audio = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Deposit to user personal voicemail box') {
                                $extension = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Deposit to Common Voicemail box') {
                                $vextension = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'External Number') {
                                $outbound_extension = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Sub Menu') {
                                $submenu = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Transfer to extension') {
                                $submenu = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Queues') {
                                $submenu = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                            if ($actions == 'Ring Groups') {
                                $submenu = !empty($allData[$i]) ? $allData[$i] : '';
                            }
                        }

                        echo Html::dropDownList('actions[]',
                            $actions,
                            ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                            [
                                'class' => 'form-control',
                                'id' => 'actions_' . $i,
                                'Disabled' => TRUE,
                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                            ]);
                        ?>
                    </div>

                    <div class="col-xs-4 col-md-3">


                        <div id="textField_<?php echo $i ?>">
                            <?php echo
                            Html::textInput('outbound_extension[]',
                                $outbound_extension,
                                [
                                    'class' => 'form-control',
                                    'Disabled' => TRUE,
                                    'placeholder' => AutoAttendantModule::t('autoattendant', 'enter_extension_outbound'),
                                    'onkeypress' => 'return isNumberOnly(event);',
                                ]);
                            ?>

                        </div>

                        <div id="textFieldWithButton_<?php echo $i ?>">
                            <?php echo
                            Html::textInput('submenu[]',
                                $submenu,
                                [
                                    'class' => 'form-control',
                                    'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                                ]);

                            ?>
                        </div>

                    </div>

                </div>
                <br>
                <?php

            }
            ?>
            <?php
            if ($detailModelError || $model->hasErrors()) {
                $actions = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['actions'] : '';
                $audio = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['audio'] : '';
                $extension = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['extension'] : '';
                $vextension = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['vextension'] : '';
                $outbound_extension = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['outbound_extension'] : '';
                $submenu = !empty($allAutoAttendantDetails['10']) ? $allAutoAttendantDetails['10']['submenu'] : '';
            } else {
                $actions = !empty($autoDetail['*']) ? $autoDetail['*'] : '';
                $audio = '';
                $extension = '';
                $vextension = '';
                $outbound_extension = '';
                $submenu = '';
                if ($actions == 'Playfile') {
                    $audio = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Deposit to user personal voicemail box') {
                    $extension = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Deposit to Common Voicemail box') {
                    $vextension = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'External Number') {
                    $outbound_extension = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Sub Menu') {
                    $submenu = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Transfer to extension') {
                    $submenu = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Queues') {
                    $submenu = !empty($allData['*']) ? $allData['*'] : '';
                }
                if ($actions == 'Ring Groups') {
                    $submenu = !empty($allData['*']) ? $allData['*'] : '';
                }
            }
            ?>
            <div class="row">
                <div class="col-xs-4 col-md-2">
                    <?php
                    echo Html::textInput('digit[]',
                        '*',
                        ['class' => 'form-control', 'readonly' => TRUE, 'id' => 'digits_star']);
                    ?>
                </div>
                <div class="col-xs-4 col-md-7">
                    <?php

                    echo Html::dropDownList('actions[]',
                        $actions,
                        ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                        [
                            'class' => 'form-control',
                            'id' => 'actions_star',
                            'Disabled' => TRUE,
                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),

                        ]);
                    ?>
                </div>
                <div class="col-xs-4 col-md-3" id="appendFieldStar">

                    <div id="textField_star">
                        <?php echo
                        Html::textInput('outbound_extension[]',
                            $outbound_extension,
                            [
                                'class' => 'form-control',
                                'Disabled' => TRUE,
                                'placeholder' => AutoAttendantModule::t('autoattendant', 'enter_extension_outbound'),
                            ]);
                        ?>

                    </div>

                    <div id="textFieldWithButton_star">
                        <?php echo
                        Html::textInput('submenu[]',
                            $submenu,
                            [
                                'class' => 'form-control',
                                'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                            ]);

                        ?>
                    </div>

                </div>

            </div>
            <br>

            <?php
            if ($detailModelError || $model->hasErrors()) {
                $actions = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['actions'] : '';
                $audio = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['audio'] : '';
                $extension = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['extension'] : '';
                $vextension = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['vextension'] : '';
                $outbound_extension = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['outbound_extension'] : '';
                $submenu = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['submenu'] : '';
            } else {
                $actions = !empty($autoDetail['#']) ? $autoDetail['#'] : '';
                $audio = '';
                $extension = '';
                $vextension = '';
                $outbound_extension = '';
                $submenu = '';
                if ($actions == 'Playfile') {
                    $audio = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Deposit to user personal voicemail box') {
                    $extension = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Deposit to Common Voicemail box') {
                    $vextension = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'External Number') {
                    $outbound_extension = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Sub Menu') {
                    $submenu = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Transfer to extension') {
                    $submenu = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Queues') {
                    $submenu = !empty($allData['#']) ? $allData['#'] : '';
                }
                if ($actions == 'Ring Groups') {
                    $submenu = !empty($allData['#']) ? $allData['#'] : '';
                }
            }
            ?>
            <div class="row">
                <div class="col-xs-4 col-md-2">
                    <?php
                    echo Html::textInput('digit[]',
                        '#',
                        ['class' => 'form-control', 'readonly' => TRUE, 'id' => 'digits_hash']);
                    ?>
                </div>
                <div class="col-xs-4 col-md-7">
                    <?php
                    echo Html::dropDownList('actions[]',
                        $actions,
                        ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                        [
                            'class' => 'form-control',
                            'id' => 'actions_hash',
                            'Disabled' => TRUE,
                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                        ]);
                    ?>
                </div>
                <div class="col-xs-4 col-md-3" id="appendFieldHash">

                    <div id="textField_hash">
                        <?php echo
                        Html::textInput('outbound_extension[]',
                            $outbound_extension,
                            [
                                'class' => 'form-control',
                                'Disabled' => TRUE,
                                'placeholder' => AutoAttendantModule::t('autoattendant', 'enter_extension_outbound'),
                            ]);
                        ?>

                    </div>

                    <div id="textFieldWithButton_hash">
                        <?php echo
                        Html::textInput('submenu[]',
                            $submenu,
                            [
                                'class' => 'form-control',
                                'Disabled' => TRUE,
                                'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                            ]); ?>

                    </div>
                </div>

            </div>
        </div>
        <br>
    </div>
    <?php try {
        ActiveForm::end();
    } catch (InvalidCallException $e) {
    } ?>
    <div class="row">
        <div class="form-group col-sm-offset-6 col-md-offset-7 col-xs-offset-2">
            <?= Html::a(AutoAttendantModule::t('autoattendant', 'back'),
                ['index', 'page' => Yii::$app->session->get('page')],
                ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
</div>