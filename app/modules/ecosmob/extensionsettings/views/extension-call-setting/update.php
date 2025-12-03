<?php

use yii\helpers\Html;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting */

$this->title = ExtensionSettingsModule::t('extensionsettings', 'update_ext_call_set') . $model->ecs_id;
$this->params['breadcrumbs'][] = ['label' => ExtensionSettingsModule::t('extensionsettings', 'ext_call_set'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ecs_id, 'url' => ['index']];
$this->params['breadcrumbs'][] = ExtensionSettingsModule::t('extensionsettings', 'update');
$this->params['pageHead'] = $this->title;
?>
</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-call-setting-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>