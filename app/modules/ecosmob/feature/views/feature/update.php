<?php

use app\modules\ecosmob\feature\FeatureModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\feature\models\Feature */

$this->title = FeatureModule::t('feature', 'update_feature_code') . $model->feature_name;
$this->params['breadcrumbs'][] = ['label' => FeatureModule::t('feature', 'feature_code'), 'url' => ['index']];
$this->params['breadcrumbs'][] = FeatureModule::t('feature', 'update');
$this->params['pageHead'] = $this->title;
?>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="feature-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>