<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;

/* @var $this yii\web\View */
/* @var $importModel app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $searchModel  \app\modules\ecosmob\didmanagement\models\DidManagementSearch */
/* @var $dataProvider */

$this->title = DidManagementModule::t('did', 'import_did');
$this->params['breadcrumbs'][] = ['label' => DidManagementModule::t('did', 'did_mang'), 'url' => ['index']];
$this->params['breadcrumbs'][] = DidManagementModule::t('did', 'import');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="did-master-import">
                    <?= $this->render('_import',
                        [
                            'importModel' => $importModel,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>