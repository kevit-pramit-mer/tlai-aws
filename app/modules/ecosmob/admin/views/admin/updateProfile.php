<?php

use app\modules\ecosmob\admin\AdminModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\admin\models\AdminMaster */

$this->params['breadcrumbs'][] = [
    'label' => AdminModule::t('admin', 'update_profile'),
];
$this->params['pageHead'] = AdminModule::t('admin', 'update_profile');
$this->title = AdminModule::t('admin', 'update_profile');
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="admin-update-profile" id="admin-update-profile">
                    <?= $this->render('form/_updateProfile', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
