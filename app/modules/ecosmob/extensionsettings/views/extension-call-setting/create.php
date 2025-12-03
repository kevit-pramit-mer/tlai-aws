<?php

use yii\helpers\Html;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting */

$this->title = ExtensionSettingsModule::t('extensionsettings', 'create_ext_call_set');
$this->params['breadcrumbs'][] = ['label' => ExtensionSettingsModule::t('extensionsettings', 'ext_call_set'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-call-setting-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>