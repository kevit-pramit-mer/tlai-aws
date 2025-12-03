<?php

use app\assets\AppAsset;
use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\autoattendant\assets\AutoAttendantAsset;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMaster */
/* @var $merged_array app\modules\ecosmob\audiomanagement\models\AudioManagement */
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

$defaultArray = ['' => 'None'];
/*$merged_array=array_merge($defaultArray, AudioManagement::getAudioList());*/
$merged_array = AudioManagement::getAudioList();
$merged_array_playfile = AudioManagement::getAudioList();


$merged_array_extensions = Extension::getExtensionList();
$merged_array_queues = QueueMaster::getQueueList();
$merged_array_ringgroups = RingGroup::getRinggroupList();
$name = $model->aam_name;
$merged_array_copysubmenu = AutoAttendantMaster::getAllSubMenuName($id, $linkId);
$merged_array_conference = ConferenceMaster::getAllConference();
/*$merged_array_audio_text=AutoAttendantMaster::getAllAudioText($id, $linkId);*/
$merged_array_audio_text = AutoAttendantMaster::getAudioTextExtList();
$merged_array_voicemail = Extension::getVoicemailList();


?>
<?php
$extensionLists = Extension::find()->all();
foreach ($extensionLists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}

$ext = ArrayHelper::map($extensionLists, 'em_extension_number', 'em_extension_name');

?>

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: auto !important;
        margin: 0 !important;
        opacity: 1 !important;
    }
</style>

<div class="row" style="margin-bottom:15px;">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'auto-attendant-master-setting-form',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
            'class' => 'row',
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="auto-attendant-setting-form" id="auto-attendant-setting-form">
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="card-tabs">
                                <div class="card-content">
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
                                    <div>
                                        <?php
                                        if ($id != $linkId) {
                                            ?>
                                            <a href="<?= Url::to(['/autoattendant/autoattendant/settings', 'id' => $linkId]); ?>">
                                            <span class="btn btn-primary btn-round-left btn-sm mt-1 mb-2 hvr-icon-back"><?= AutoAttendantModule::t('autoattendant',
                                                    'return_to_parent') ?>
                                            </span>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m8 pul ivr-settings border-left">
                            <div id="input-fields" class="card-tabs">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model,
                                                    'aam_name',
                                                    [
                                                        'inputOptions' => [
                                                            //'autofocus' => 'autofocus',
                                                            'class' => 'form-control',
                                                            'tabindex' => '1',
                                                        ],
                                                    ]
                                                )->textInput(
                                                    [
                                                        'maxlength' => TRUE,
                                                    ]
                                                )->label($model->getAttributeLabel('aam_name')) ?>

                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model, 'aam_extension')->textInput(
                                                    [
                                                        'type' => 'number',
                                                        'readOnly' => 'readOnly',
                                                    ]
                                                )->label($model->getAttributeLabel('aam_extension')) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($model->isNewRecord) { ?>
                                        <div class="row">
                                            <div class="col s12 m6">

                                            </div>
                                            <div class="col s12 m6">
                                                <div style="color:#55595c"><i>
                                                        * <?= AutoAttendantModule::t('autoattendant', 'cant_change_extension_number'); ?></i>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?php echo $form->field($model, 'aam_greet_long', ['options' => ['class' => '']])
                                                        ->dropDownList($merged_array)
                                                        ->label($model->getAttributeLabel('aam_greet_long')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?= $form->field($model, 'aam_greet_short', ['options' => ['class' => '']])
                                                        ->dropDownList($merged_array)
                                                        ->label($model->getAttributeLabel('aam_greet_short')) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!--                                        <div class="col s12 m6">-->
                                        <!--                                            <div class="input-field">-->
                                        <!--                                                <div class="select-wrapper">-->
                                        <!--                                                    --><?php //echo $form->field($model, 'aam_timeout_prompt', ['options' => ['class' => '']])
                                        //                                                        ->dropDownList($merged_array)
                                        //                                                        ->label($model->getAttributeLabel('aam_timeout_prompt')) ?>
                                        <!--                                                </div>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->

                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?= $form->field($model, 'aam_failure_prompt', ['options' => ['class' => '']])
                                                        ->dropDownList($merged_array)
                                                        ->label($model->getAttributeLabel('aam_failure_prompt')) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?php echo $form->field($model, 'aam_invalid_sound', ['options' => ['class' => '']])
                                                        ->dropDownList($merged_array)
                                                        ->label($model->getAttributeLabel('aam_invalid_sound')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?= $form->field($model, 'aam_exit_sound', ['options' => ['class' => '']])
                                                        ->dropDownList($merged_array)
                                                        ->label($model->getAttributeLabel('aam_exit_sound')) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model, 'aam_timeout')->textInput(
                                                    [
                                                        'maxlength' => 5,
                                                    ]
                                                )->label($model->getAttributeLabel('aam_timeout')) ?>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model, 'aam_inter_digit_timeout')->textInput(
                                                    [
                                                        'maxlength' => 5,
                                                    ]
                                                )->label($model->getAttributeLabel('aam_inter_digit_timeout')) ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field(
                                                    $model,
                                                    'aam_max_failures')->textInput(
                                                    [
                                                        'maxlength' => 5,
                                                    ]
                                                )->label($model->getAttributeLabel('aam_max_failures')) ?>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model, 'aam_max_timeout')->textInput(
                                                    [
                                                        'maxlength' => 5,
                                                    ]
                                                )->label($model->getAttributeLabel('aam_max_timeout')) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?php
                                                    echo $form->field($model, 'aam_language', ['options' => ['class' => '']])->dropDownList([
                                                            'en' => Yii::t('app', 'english'),
                                                            'es' => Yii::t('app', 'spanish'),
                                                        ]
                                                    )->label($model->getAttributeLabel('aam_language'));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <div class="select-wrapper">
                                                    <?php if (!$model->isNewRecord) {
                                                        echo $form->field($model, 'aam_status', ['options' => ['class' => '']])->dropDownList([
                                                                '1' => Yii::t('app', 'active'),
                                                                '0' => Yii::t('app', 'inactive'),
                                                            ]
                                                        )->label($model->getAttributeLabel('aam_status'));
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <?= $form->field($model, 'aam_digit_len')->textInput(
                                                    [
                                                        'type' => 'number',
                                                        'maxlength' => 2,
                                                        'min' => 1,
                                                        'max' => 20,
                                                        'oninput' => "this.value = this.value.replace(/^0+/, ''); if (this.value.length > 2) { this.value = this.value.slice(0, 2); }"
                                                    ]
                                                )->label($model->getAttributeLabel('aam_digit_len')) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12">
                                            <div class="col s12 m6 input-field  p-0">
                                                <div class="col s12 p-0">
                                                    <p> <?= $model->getAttributeLabel('aam_direct_dial') ?>
                                                        : </p>
                                                </div>
                                                <div class="col s12 p-0">
                                                    <div class="switch">
                                                        <label>
                                                            <?= AutoAttendantModule::t('autoattendant', 'off') ?>
                                                            <?= Html::activeCheckbox($model, 'aam_direct_dial', ['uncheck' => 0, 'label' => FALSE]) ?>
                                                            <span class="lever"></span>
                                                            <?= AutoAttendantModule::t('autoattendant', 'on') ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6 input-field m-p-0">
                                                <div class="col s12 p-0">
                                                    <p> <?= $model->getAttributeLabel('aam_transfer_on_failure') ?>
                                                        : </p>
                                                </div>
                                                <div class="col s12 p-0">
                                                    <div class="switch">
                                                        <label>
                                                            <?= AutoAttendantModule::t('autoattendant', 'off') ?>
                                                            <?= Html::activeCheckbox($model, 'aam_transfer_on_failure', ['uncheck' => 0, 'label' => FALSE, 'id' => 'checkbtn']) ?>
                                                            <span class="lever"></span>
                                                            <?= AutoAttendantModule::t('autoattendant', 'on') ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="transfer_on_failure">
                                                <div class="col s12 m6 input-field">
                                                    <div class="input-field">
                                                        <div class="select-wrapper">
                                                            <?= $form->field($model, 'aam_transfer_extension_type', ['options' => ['class' => '', 'id' => 'transfer_extension_type']])
                                                                ->dropDownList(['INTERNAL' => Yii::t('app', 'internal'),
                                                                    'EXTERNAL' => Yii::t('app', 'external')], ['prompt' =>
                                                                    AutoAttendantModule::t('autoattendant', 'select_transfer_type')])
                                                                ->label(AutoAttendantModule::t('autoattendant', 'transfer_extension_type')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s12 m6 input-field">
                                                    <div class="input-field" id="transfer_internal">
                                                        <div class="select-wrapper">
                                                            <?php
                                                            echo $form->field($model, 'aam_transfer_extension', ['options' => ['class' => '', 'id' => 'select_transfer_internal']])->dropDownList($ext, ['prompt' => AutoAttendantModule::t('autoattendant', 'select_internal')])->label(AutoAttendantModule::t('autoattendant', 'aam_transfer_on_failure'));
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 input-field input-right" id="transfer_external">
                                                        <?= $form->field($model, 'aam_transfer_extension')->textInput(['maxlength' => true, 'class' => '', 'id' => 'select_transfer_external'])->label(AutoAttendantModule::t('autoattendant', 'aam_transfer_on_failure')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="section"></div>
                                    <div class="divider"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th><?php echo AutoAttendantModule::t('autoattendant', 'digit'); ?></th>
                                                <th><?php echo AutoAttendantModule::t('autoattendant', 'action'); ?></th>
                                                <th class="center"><?php echo AutoAttendantModule::t('autoattendant', 'value'); ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="table-responsive setting-table-data">
                                        <div class="digit-setup margin-top-15 p-0">
                                            <table>
                                                <?php
                                                for ($i = 0; $i < 10; $i++) {
                                                    echo '<tr><td class=""><input class="" type="text" value="' . $i
                                                        . '" disabled></td></tr>';
                                                }
                                                echo '<tr><td class=""><input class="" type="text" value="*" disabled></td></tr>';
                                                echo '<tr><td class=""><input class="" type="text" value="#" disabled></td></tr>';
                                                ?>
                                            </table>    
                                        </div>
                                        <div class="action-value-setup">
                                            <div class="sortable-list list-handle">
                                                <ul data-plugin="sortable" id="appendMain" data-handle=".arrow_move"
                                                    data-animation="150"
                                                    aria-dropeffect="move">
                                                    <?php
                                                    for ($i = 0; $i < 10; $i++) {

                                                        ?>
                                                        <li class="height-drag btn-default"><span class="handle col s2"><i
                                                                        class="arrow_move material-icons"
                                                                        style="height: 40px;">dehaze</i></span>
                                                            <div class="row">

                                                                <div class="col s6 m6">

                                                                    <?php
                                                                    if ($detailModelError || $model->hasErrors()) {

                                                                        $actions = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['actions']))
                                                                            ? $allAutoAttendantDetails[$i]['actions'] : '';

                                                                        $audio = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['audio']))
                                                                            ? $allAutoAttendantDetails[$i]['audio']
                                                                            : '';
                                                                        $outbound_extension = (!empty($allAutoAttendantDetails[$i]) && $allAutoAttendantDetails[$i]['outbound_extension'])
                                                                            ? $allAutoAttendantDetails[$i]['outbound_extension'] : '';

                                                                        $submenu = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['submenu']))
                                                                            ? $allAutoAttendantDetails[$i]['submenu'] : '';

                                                                        $transfer_extension = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['transfer_extension']))
                                                                            ? $allAutoAttendantDetails[$i]['transfer_extension'] : '';

                                                                        $queues = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['queues']))
                                                                            ? $allAutoAttendantDetails[$i]['queues'] : '';

                                                                        $ring_groups = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['ring_groups']))
                                                                            ? $allAutoAttendantDetails[$i]['ring_groups'] : '';

                                                                        $copy_sub_menu = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['copy_sub_menu']))
                                                                            ? $allAutoAttendantDetails[$i]['copy_sub_menu'] : '';

                                                                        $conference = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['conference']))
                                                                            ? $allAutoAttendantDetails[$i]['conference'] : '';

                                                                        $audio_text = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['audio_text']))
                                                                            ? $allAutoAttendantDetails[$i]['audio_text'] : '';

                                                                        $voicemail = (!empty($allAutoAttendantDetails[$i]) && isset($allAutoAttendantDetails[$i]['voicemail']))
                                                                            ? $allAutoAttendantDetails[$i]['voicemail'] : '';

                                                                    } else {
                                                                        $actions = !empty($autoDetail[$i]) ? $autoDetail[$i] : '';
                                                                        $audio = '';
                                                                        $extension = '';
                                                                        $vextension = '';
                                                                        $outbound_extension = '';
                                                                        $transfer_extension = '';
                                                                        $queues = '';
                                                                        $submenu = '';
                                                                        $ring_groups = '';
                                                                        $copy_sub_menu = '';
                                                                        $conference = '';
                                                                        $audio_text = '';
                                                                        $voicemail = '';
                                                                        if ($actions == 'Playfile') {
                                                                            $audio = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Deposit to user personal voicemail box') {
                                                                            $extension = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }

                                                                        if ($actions == 'External Number') {
                                                                            $outbound_extension = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Sub Menu') {
                                                                            $submenu = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Dial by name within User Group') {
                                                                            // $usergroups = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Transfer to extension') {
                                                                            $transfer_extension = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Queues') {
                                                                            $queues = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Ring Groups') {
                                                                            $ring_groups = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Copy Sub Menu') {
                                                                            $copy_sub_menu = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Conference') {
                                                                            $conference = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'IVR') {
                                                                            $audio_text = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                        if ($actions == 'Voicemail') {
                                                                            $voicemail = !empty($allData[$i]) ? $allData[$i] : '';
                                                                        }
                                                                    }

                                                                    echo Html::dropDownList('actions[]',
                                                                        $actions,
                                                                        ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                                                                        [
                                                                            'id' => 'actions_' . $i,
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>

                                                                <div class="col s6 m6 value-sets">
                                                                    <div id="dropDownFieldAudio_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('audio[]',
                                                                            $audio,
                                                                            $merged_array_playfile,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>

                                                                    </div>
                                                                    <div
                                                                            id="dropDownFieldTransferExtentions_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('transfer_extension[]',
                                                                            $transfer_extension,
                                                                            $merged_array_extensions,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>

                                                                    </div>
                                                                    <div id="dropDownFieldQueues_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('queues[]',
                                                                            $queues,
                                                                            $merged_array_queues,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>

                                                                    </div>
                                                                    <div id="dropDownFieldRingGroups_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('ring_groups[]',
                                                                            $ring_groups,
                                                                            $merged_array_ringgroups,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>

                                                                    </div>
                                                                    <div id="dropDownFieldCopySubmenu_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('copy_sub_menu[]',
                                                                            $copy_sub_menu,
                                                                            $merged_array_copysubmenu,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>
                                                                    </div>
                                                                    <div id="dropDownFieldConference_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('conference[]',
                                                                            $conference,
                                                                            $merged_array_conference,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>
                                                                    </div>
                                                                    <div id="dropDownFieldAudiotext_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('audio_text[]',
                                                                            $audio_text,
                                                                            $merged_array_audio_text,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>
                                                                    </div>
                                                                    <div id="dropDownFieldVoicemail_<?php echo $i ?>">
                                                                        <?php

                                                                        echo Html::dropDownList('voicemail[]',
                                                                            $voicemail,
                                                                            $merged_array_voicemail,
                                                                            [
                                                                                'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                            ]);
                                                                        ?>
                                                                    </div>


                                                                    <div id="textField_<?php echo $i ?>">
                                                                        <?php echo Html::textInput('outbound_extension[]',
                                                                            $outbound_extension,
                                                                            [
                                                                                'maxlength' => 15,
                                                                                'placeholder' => AutoAttendantModule::t('autoattendant',
                                                                                    'enter_extension_outbound'),
                                                                                'onkeypress' => 'return isNumAstValue(event);',
                                                                            ]);
                                                                        ?>

                                                                    </div>

                                                                    <div id="textFieldWithButton_<?php echo $i ?>">
                                                                        <?php echo Html::textInput('submenu[]',
                                                                            $submenu,
                                                                            [
                                                                                'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                                                                            ]);

                                                                        ?>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </li>
                                                        <?php

                                                    }
                                                    ?>
                                                    <?php
                                                    if ($detailModelError || $model->hasErrors()) {
                                                        $actions = !empty($allAutoAttendantDetails['10']['actions'])
                                                            ? $allAutoAttendantDetails['10']['actions']
                                                            : '';
                                                        $audio = !empty($allAutoAttendantDetails['10']['audio'])
                                                            ? $allAutoAttendantDetails['10']['audio'] : '';
                                                        $extension = !empty($allAutoAttendantDetails['10']['extension'])
                                                            ? $allAutoAttendantDetails['10']['extension']
                                                            : '';
                                                        $vextension = !empty($allAutoAttendantDetails['10']['vextension'])
                                                            ? $allAutoAttendantDetails['10']['vextension'] : '';
                                                        $outbound_extension = !empty($allAutoAttendantDetails['10']['outbound_extension'])
                                                            ? $allAutoAttendantDetails['10']['outbound_extension'] : '';
                                                        $submenu = !empty($allAutoAttendantDetails['10']['submenu'])
                                                            ? $allAutoAttendantDetails['10']['submenu']
                                                            : '';
                                                        $transfer_extension = !empty($allAutoAttendantDetails['10']['transfer_extension'])
                                                            ? $allAutoAttendantDetails['10']['transfer_extension']
                                                            : '';
                                                        $queues = !empty($allAutoAttendantDetails['10']['queues'])
                                                            ? $allAutoAttendantDetails['10']['queues']
                                                            : '';
                                                        $ring_groups = !empty($allAutoAttendantDetails['10']['ring_groups'])
                                                            ? $allAutoAttendantDetails['10']['ring_groups']
                                                            : '';
                                                        $copy_sub_menu = !empty($allAutoAttendantDetails['10']['copy_sub_menu'])
                                                            ? $allAutoAttendantDetails['10']['copy_sub_menu']
                                                            : '';
                                                        $conference = !empty($allAutoAttendantDetails['10']['conference'])
                                                            ? $allAutoAttendantDetails['10']['conference']
                                                            : '';
                                                        $audio_text = !empty($allAutoAttendantDetails['10']['audio_text'])
                                                            ? $allAutoAttendantDetails['10']['audio_text']
                                                            : '';
                                                        $voicemail = !empty($allAutoAttendantDetails['10']['voicemail'])
                                                            ? $allAutoAttendantDetails['10']['voicemail']
                                                            : '';
                                                    } else {
                                                        $actions = !empty($autoDetail['*']) ? $autoDetail['*'] : '';
                                                        $audio = '';
                                                        $extension = '';
                                                        $vextension = '';
                                                        $outbound_extension = '';
                                                        $submenu = '';
                                                        $transfer_extension = '';
                                                        $queues = '';
                                                        $ring_groups = '';
                                                        $copy_sub_menu = '';
                                                        $conference = '';
                                                        $audio_text = '';
                                                        $voicemail = '';
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
                                                        if ($actions == 'Dial by name within User Group') {
                                                            // $usergroups = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'Transfer to extension') {
                                                            $transfer_extension = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'Queues') {
                                                            $queues = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'Ring Groups') {
                                                            $ring_groups = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'Copy Sub Menu') {
                                                            $copy_sub_menu = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'Conference') {
                                                            $conference = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'IVR') {
                                                            $audio_text = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                        if ($actions == 'voicemail') {
                                                            $voicemail = !empty($allData['*']) ? $allData['*'] : '';
                                                        }
                                                    }
                                                    ?>
                                                    <li class="height-drag btn-default">
                                                        <span class="handle col s2">
                                                            <i class="arrow_move material-icons"
                                                            style="height: 40px;">dehaze</i>
                                                        </span>
                                                        <div class="row">
                                                            <div class="col s6 m6">
                                                                <?php

                                                                echo Html::dropDownList('actions[]',
                                                                    $actions,
                                                                    ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                                                                    [
                                                                        'id' => 'actions_star',
                                                                        'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                    ]);
                                                                ?>
                                                            </div>
                                                            <div class="col s6 m6" id="appendFieldStar">
                                                                <div id="dropDownFieldAudio_star">
                                                                    <?php
                                                                    echo Html::dropDownList('audio[]',
                                                                        $audio,
                                                                        $merged_array,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>

                                                                <div id="dropDownFieldTransferExtentions_star">
                                                                    <?php

                                                                    echo Html::dropDownList('transfer_extension[]',
                                                                        $transfer_extension,
                                                                        $merged_array_extensions,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldQueues_star">
                                                                    <?php

                                                                    echo Html::dropDownList('queues[]',
                                                                        $queues,
                                                                        $merged_array_queues,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldRingGroups_star">
                                                                    <?php

                                                                    echo Html::dropDownList('ring_groups[]',
                                                                        $ring_groups,
                                                                        $merged_array_ringgroups,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldCopySubmenu_star">
                                                                    <?php

                                                                    echo Html::dropDownList('copy_sub_menu[]',
                                                                        $copy_sub_menu,
                                                                        $merged_array_copysubmenu,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>

                                                                <div id="dropDownFieldConference_star">
                                                                    <?php

                                                                    echo Html::dropDownList('conference[]',
                                                                        $conference,
                                                                        $merged_array_conference,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>
                                                                <div id="dropDownFieldAudiotext_star">
                                                                    <?php

                                                                    echo Html::dropDownList('audio_text[]',
                                                                        $audio_text,
                                                                        $merged_array_audio_text,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>
                                                                <div id="dropDownFieldVoicemail_star">
                                                                    <?php

                                                                    echo Html::dropDownList('voicemail[]',
                                                                        $voicemail,
                                                                        $merged_array_voicemail,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>


                                                                <div id="textField_star">
                                                                    <?php echo Html::textInput('outbound_extension[]',
                                                                        $outbound_extension,
                                                                        [
                                                                            'maxlength' => 15,
                                                                            'placeholder' => AutoAttendantModule::t('autoattendant',
                                                                                'enter_extension_outbound'),
                                                                            'onkeypress' => 'return isAlphaNumAstValue(event)',
                                                                        ]);
                                                                    ?>

                                                                </div>

                                                                <div id="textFieldWithButton_star">
                                                                    <?php echo Html::textInput('submenu[]',
                                                                        $submenu,
                                                                        [
                                                                            'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                                                                        ]);

                                                                    ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </li>

                                                    <?php
                                                    if ($detailModelError || $model->hasErrors()) {
                                                        $actions = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['actions'] : '';
                                                        $audio = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['audio']
                                                            : '';
                                                        $extension = !empty($allAutoAttendantDetails['11']['extension'])
                                                            ? $allAutoAttendantDetails['11']['extension'] : '';
                                                        $vextension = !empty($allAutoAttendantDetails['11']['vextension'])
                                                            ? $allAutoAttendantDetails['11']['vextension'] : '';
                                                        $outbound_extension = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['outbound_extension']
                                                            : '';
                                                        $submenu = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['submenu'] : '';

                                                        //$transfer_extension = !empty($allAutoAttendantDetails['11']) ? $allAutoAttendantDetails['11']['transfer_extension'] : '';

                                                        $transfer_extension = (isset($allAutoAttendantDetails['11']['transfer_extension']) && !empty($allAutoAttendantDetails['11']['transfer_extension'])) ? $allAutoAttendantDetails['11']['transfer_extension'] : '';


                                                        /* $queues = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['queues'] : ''; */

                                                        $queues = (isset($allAutoAttendantDetails['11']['queues']) && !empty($allAutoAttendantDetails['11']['queues'])) ? $allAutoAttendantDetails['11']['queues'] : '';


                                                        /* $ring_groups = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['ring_groups'] : ''; */

                                                        $ring_groups = (isset($allAutoAttendantDetails['11']['ring_groups']) && !empty($allAutoAttendantDetails['11']['ring_groups'])) ? $allAutoAttendantDetails['11']['ring_groups'] : '';


                                                        /* $copy_sub_menu = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['copy_sub_menu'] : ''; */

                                                        $copy_sub_menu = (isset($allAutoAttendantDetails['11']['copy_sub_menu']) && !empty($allAutoAttendantDetails['11']['copy_sub_menu'])) ? $allAutoAttendantDetails['11']['copy_sub_menu'] : '';


                                                        /* $conference = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['conference'] : ''; */
                                                        $conference = (isset($allAutoAttendantDetails['11']['conference']) && !empty($allAutoAttendantDetails['11']['conference'])) ? $allAutoAttendantDetails['11']['conference'] : '';

                                                        /* $audio_text = !empty($allAutoAttendantDetails['11'])
                                                            ? $allAutoAttendantDetails['11']['audio_text'] : ''; */
                                                        $audio_text = (isset($allAutoAttendantDetails['11']['audio_text']) && !empty($allAutoAttendantDetails['11']['audio_text'])) ? $allAutoAttendantDetails['11']['audio_text'] : '';
                                                        $voicemail = (isset($allAutoAttendantDetails['11']['audio_text']) && !empty($allAutoAttendantDetails['11']['voicemail'])) ? $allAutoAttendantDetails['11']['voicemail'] : '';
                                                    } else {
                                                        $actions = !empty($autoDetail['#']) ? $autoDetail['#'] : '';
                                                        $audio = '';
                                                        $extension = '';
                                                        $vextension = '';
                                                        $outbound_extension = '';
                                                        $submenu = '';
                                                        $copy_sub_menu = '';
                                                        $conference = '';
                                                        $audio_text = '';
                                                        $voicemail = '';
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
                                                        if ($actions == 'Dial by name within User Group') {
                                                            // $usergroups = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Transfer to extension') {
                                                            $transfer_extension = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Queues') {
                                                            $queues = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Ring Groups') {
                                                            $ring_groups = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Copy Sub Menu') {
                                                            $copy_sub_menu = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Conference') {
                                                            $conference = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'IVR') {
                                                            $audio_text = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                        if ($actions == 'Voicemail') {
                                                            $voicemail = !empty($allData['#']) ? $allData['#'] : '';
                                                        }
                                                    }
                                                    ?>
                                                    <li class="height-drag btn-default">
                                                        <span class="handle col s2">
                                                            <i class="arrow_move material-icons"
                                                            style="height: 40px;">dehaze</i>
                                                        </span>
                                                        <div class="row">
                                                            <div class="col s6 m6">
                                                                <?php
                                                                echo Html::dropDownList('actions[]',
                                                                    $actions,
                                                                    ArrayHelper::map($autoAttendantKeys, 'aak_key_name', 'aak_key_name'),
                                                                    [
                                                                        'id' => 'actions_hash',
                                                                        'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                    ]);
                                                                ?>
                                                            </div>
                                                            <div class="col s6 m6" id="appendFieldHash">
                                                                <div id="dropDownFieldAudio_hash">
                                                                    <?php
                                                                    echo Html::dropDownList('audio[]',
                                                                        $audio,
                                                                        $merged_array,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>
                                                                <div id="dropDownFieldTransferExtentions_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('transfer_extension[]',
                                                                        $transfer_extension,
                                                                        $merged_array_extensions,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldQueues_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('queues[]',
                                                                        $queues,
                                                                        $merged_array_queues,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldRingGroups_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('ring_groups[]',
                                                                        $ring_groups,
                                                                        $merged_array_ringgroups,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>
                                                                <div id="dropDownFieldCopySubmenu_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('copy_sub_menu[]',
                                                                        $copy_sub_menu,
                                                                        $merged_array_copysubmenu,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>

                                                                </div>


                                                                <div id="dropDownFieldConference_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('conference[]',
                                                                        $conference,
                                                                        $merged_array_conference,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>
                                                                <div id="dropDownFieldAudiotext_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('audio_text[]',
                                                                        $audio_text,
                                                                        $merged_array_audio_text,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>
                                                                <div id="dropDownFieldVoicemail_hash">
                                                                    <?php

                                                                    echo Html::dropDownList('voicemail[]',
                                                                        $voicemail,
                                                                        $merged_array_voicemail,
                                                                        [
                                                                            'prompt' => AutoAttendantModule::t('autoattendant', 'select'),
                                                                        ]);
                                                                    ?>
                                                                </div>


                                                                <div id="textField_hash">
                                                                    <?php echo Html::textInput('outbound_extension[]',
                                                                        $outbound_extension,
                                                                        [
                                                                            'maxlength' => 15,
                                                                            'placeholder' => AutoAttendantModule::t('autoattendant',
                                                                                'enter_extension_outbound'),
                                                                            'onkeypress' => 'return isAlphaNumAstValue(event)',
                                                                        ]);
                                                                    ?>

                                                                </div>

                                                                <div id="textFieldWithButton_hash">
                                                                    <?php echo Html::textInput('submenu[]',
                                                                        $submenu,
                                                                        [
                                                                            'placeholder' => AutoAttendantModule::t('autoattendant', 'submenu'),
                                                                        ]); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
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
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(AutoAttendantModule::t('autoattendant', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(
                    $model->isNewRecord ? AutoAttendantModule::t(
                        'autoattendant',
                        'save'
                    ) : AutoAttendantModule::t('autoattendant', 'update'),
                    [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4'
                            : 'btn waves-effect waves-light cyan accent-8',
                    ]
                ) ?>
            </div>
        </div>
        <?php try {
            ActiveForm::end();
        } catch (InvalidCallException $e) {
        } ?>

        <?php
        $this->registerJsFile('@web/theme/assets/global/plugins/Sortable/Sortable.min.js',
            [
                'depends' => AppAsset::className(),
                'position' => View::POS_END,
            ]);
        ?>
    </div>

    <script type="text/javascript">
        var firstload = 1;

        $(document).ready(function () {
            $('#transfer_extension_type').trigger('change');
            transfterFailure();
        });

        $("#checkbtn").click(function () {
            transfterFailure();
        });

        function transfterFailure() {
            let checkval = $("#checkbtn").is(":checked");
            if (checkval) {
                $('#transfer_on_failure').show();
            } else {
                $('#transfer_on_failure').hide();
            }
        }

        /* Week Off type hide show Start */
        $(document).on('change', '#transfer_extension_type', function () {
            firstload = 0;

            if (!firstload) {
                //$('#transfer_external').find('input').val('');
                //$("#transfer_external").parent().find('.help-block').html('');
                //$('#transfer_internal select').val('').select2('destroy').select2();
            }


            if ($(this).find(':selected').val() == 'INTERNAL') {

                $('#transfer_external').find('input').val('');
                // select box will appear and remove disable
                $("#transfer_internal").show();
                $('#transfer_internal').find('select').removeAttr('disabled');

                // input box will hide and add disable

                $("#transfer_external").hide();
                $('#transfer_external').find('input').attr('disabled', 'disabled');

                $('#select_transfer_internal select').select2();


            } else if ($(this).find(':selected').val() == 'EXTERNAL') {

                // input box will appear and remove disable
                $("#transfer_external").show();
                $('#transfer_external').find('input').removeAttr('disabled');

                // select box will hide and add disable
                $("#transfer_internal").hide();
                $('#transfer_internal').find('select').attr('disabled', 'disabled');

                $("#week_off_queue").hide();
                $('#week_off_queue').find('select').attr('disabled', 'disabled');

            } else {
                $("#transfer_external").hide();
                $("#transfer_internal").hide();

                $('#transfer_internal').find('select').attr('disabled', 'disabled');
                $('#transfer_external').find('input').attr('disabled', 'disabled');

            }

        });
        /*  Week of type hide show end */
    </script>


    <script type="text/javascript">
        $(document).on("keypress keyup blur", "#autoattendantmaster-aam_timeout, #autoattendantmaster-aam_inter_digit_timeout, #autoattendantmaster-aam_max_failures, #autoattendantmaster-aam_max_timeout, #autoattendantmaster-aam_digit_len", function (e) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    </script>
    </div>
</div>

