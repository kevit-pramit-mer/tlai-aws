<?php

use app\modules\ecosmob\license\models\LicenseTicketManagement;

/* @var $this yii\web\View */
/* @var $licenseTicketModal LicenseTicketManagement */
/* @var $searchModel LicenseTicketManagement */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $data */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'account_info'),
];
$this->params['pageHead'] = Yii::t('app', 'account_info');
$this->title = Yii::t('app', 'account_info');
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="license-management" id="license-management">
                    <?= $this->render('form/_accountInfo', [
                        'data' => $data,
                        'licenseTicketModal' => $licenseTicketModal,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
