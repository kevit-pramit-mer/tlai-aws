<?php

use app\modules\ecosmob\parkinglot\models\ParkingLot;
use app\modules\ecosmob\parkinglot\ParkingLotModule;

/* @var $this yii\web\View */
/* @var $model ParkingLot */

$this->title = ParkingLotModule::t('parkinglot', 'update_parking_lot') . $model->name;
$this->params['breadcrumbs'][] = ['label' => ParkingLotModule::t('parkinglot', 'parking_lot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ParkingLotModule::t('parkinglot', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="group-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>