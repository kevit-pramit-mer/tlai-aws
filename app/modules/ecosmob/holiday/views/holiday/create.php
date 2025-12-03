<?php

use app\modules\ecosmob\holiday\HolidayModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\holiday\models\Holiday */

$this->title = HolidayModule::t('hd', 'create_hd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'holiday'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="holiday-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>