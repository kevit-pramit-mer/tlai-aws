<?php

use app\modules\ecosmob\conference\ConferenceModule;
use app\modules\ecosmob\conference\models\ConferenceControls;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\conference\assets\ConferenceAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceControls */
/* @var $multiModel */
/* @var $dataProvider */

ConferenceAsset::register($this);
?>
<?php
$conferenceControlAction = ArrayHelper::map(ConferenceControls::findAll(['cm_id' => 0]), 'cc_action', 'cc_action');
$conferenceControlDigits = ArrayHelper::map(ConferenceControls::findAll(['cm_id' => 0]), 'cc_digits', 'cc_digits');
sort($conferenceControlDigits);
?>
<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'conference-master-configuration-form',
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field col s12'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="conference-configuration-form" id="conference-configuration-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-6 center"><?php echo ConferenceModule::t('conference', 'cc_digits'); ?></th>
                                        <th class="center"><?php echo ConferenceModule::t('conference', 'cc_action'); ?></th>
                                        <th class="col-sm-1"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($multiModel as $key => $model): ?>
                                        <tr>
                                            <td class="center"><?= $model->cc_digits ?></td>
                                            <td class="center"><?= $form->field($model, "[$key]cc_action")->dropDownList($conferenceControlAction, ['prompt' => ConferenceModule::t('conference', 'select')])->label(false) ?></td>
                                            <td><span style="color: red">*</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-4"><?php echo ConferenceModule::t('conference', 'cc_action_d'); ?></th>
                                        <th class="center"><?php echo ConferenceModule::t('conference', 'cc_desc'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'mute') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'mute_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'deaf_mute') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'deaf_mute_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'energy_up') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'energy_up_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'energy_equ') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'energy_equ_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'energy_dn') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'energy_dn_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_up') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_up_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_dn') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_dn_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_zero') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_talk_zero_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_up') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_up_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_dn') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_dn_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_zero') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'vol_listen_zero_desc') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= ConferenceModule::t('conference', 'hangup') ?></td>
                                        <td><?= ConferenceModule::t('conference', 'hangup_desc') ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(ConferenceModule::t('conference', 'cancel'),
                    Yii::$app->session->get('conf_redirect_to'),
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(ConferenceModule::t('conference', 'update'),
                    [
                        'class' => 'btn waves-effect waves-light cyan accent-8',
                    ]
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('select').select2();
    });
</script>
