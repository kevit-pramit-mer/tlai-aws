<?php


use app\modules\ecosmob\admin\AdminModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $timezoneModel app\modules\ecosmob\timezone\models\Timezone */
?>
<div class="admin-timezone-setting-form" id="admin-timezone-setting-form">
    <?php $form = ActiveForm::begin(
        [
            'id' => 'admin-timezone-setting-form',
            'class' => 'form-horizontal',
            'method' => 'post',
            'action' => Yii::$app->urlManager->createUrl(
                '/admin/admin/timezone-setting'
            ),
        ]
    ); ?>
    <div class="card-accordions">
        <div id="accordion-service" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header card-custom">
                    <a class="card-title" data-toggle="collapse" data-parent="#accordion" href="#collapseTime">
                        <h6 class="mb-0"><span class="fa fa-clock-o"></span>
                            <?= AdminModule::t('admin', 'timezone_settings') ?>
                        </h6>
                    </a>
                </div>
                <div id="collapseTime" class="collapse">
                    <div class="card-block">
                        <div class="form-group">
                            <?= $form->field($timezoneModel, 'tz_zone',
                                ['options' => ['class' => 'col-xs-12 col-md-6']])->DropDownList(
                               [], [
                                    'prompt' => 'please select TimeZone',
                                ]
                            ) ?>
                        </div>
                    </div>
                    <div class="hseparator">
                    </div>
                    <div class="row">
                        <div class="form-group  col-xs-12 col-md-offset-10 ">
                            <?= Html::submitButton(
                                AdminModule::t('admin', 'update'), ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>