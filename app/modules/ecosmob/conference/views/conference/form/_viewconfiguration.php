<?php

use app\modules\ecosmob\conference\ConferenceModule;
use app\modules\ecosmob\conference\models\ConferenceControls;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceControls */
/* @var $multiModel */
/* @var $dataProvider */

?>
<?php
$conferenceControlAction = ArrayHelper::map(ConferenceControls::findAll(['cm_id' => 0]), 'cc_action', 'cc_action');
$conferenceControlDigits = ArrayHelper::map(ConferenceControls::findAll(['cm_id' => 0]), 'cc_digits', 'cc_digits');
sort($conferenceControlDigits);
?>
<div class="conference-configuration-form"
     id="conference-configuration-form">

    <?php $form = ActiveForm::begin([
        'id' => 'conference-master-configuration-form',
    ]); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="card card-default">
                <div class="card-header">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-sm-6 text-center"><?php echo ConferenceModule::t('conference',
                                    'cc_digits'); ?></th>
                            <th class="text-center"><?php echo ConferenceModule::t('conference', 'cc_action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($multiModel as $key => $model): ?>
                            <tr>
                                <td class="text-center"><?= $model->cc_digits ?></td>
                                <td class="text-center"><?= $form->field($model,
                                        "[$key]cc_action")->dropDownList($conferenceControlAction,
                                        ['prompt' => ConferenceModule::t('conference', 'select'), 'disabled' => true,])->label(false) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="hseparator"></div>
                    <div class="row">
                        <div class="form-group col-sm-offset-5 col-md-offset-5 col-xs-offset-2">
                            <?= Html::a(ConferenceModule::t('conference', 'back'),
                                Yii::$app->session->get('conf_redirect_to'),
                                ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="card card-default">
                <div class="card-header">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-sm-4"><?php echo ConferenceModule::t('conference', 'cc_action_d'); ?></th>
                            <th class="text-center"><?php echo ConferenceModule::t('conference', 'cc_desc'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= ConferenceModule::t('conference', 'mute') ?></td>
                            <td><?= ConferenceModule::t('conference', 'mute_desc') ?></td>
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
    <?php ActiveForm::end(); ?>
</div>
