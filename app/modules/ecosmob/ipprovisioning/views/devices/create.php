<?php

use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ipprovisioning\models\Devices */

$this->title = IpprovisioningModule::t('app', 'create_device');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="devices-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>