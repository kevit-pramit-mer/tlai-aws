<?php

use app\modules\ecosmob\audiomanagement\AudioManagementModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */

$this->title = AudioManagementModule::t('am', 'create_audio_library');
$this->params['breadcrumbs'][] = ['label' => AudioManagementModule::t('am', 'audio_libraries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="prompt-list-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
