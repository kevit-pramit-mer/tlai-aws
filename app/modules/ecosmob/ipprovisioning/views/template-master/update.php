<?php

use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ipprovisioning\models\Devices */
/* @var $deviceTemplate */
/* @var $templateDetails */
/* @var $codecs */
/* @var $acodec */

$this->title = IpprovisioningModule::t('app', 'update_template') .': '. $model->template_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'update');
$this->params['pageHead'] = $this->title;
?>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="devices-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'deviceTemplate' => $deviceTemplate,
                        'templateDetails' => $templateDetails,
                        'codecs' => $codecs,
                        'acodec' => $acodec
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>