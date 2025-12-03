<?php

use app\modules\ecosmob\audiomanagement\AudioManagementModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */

$this->title = AudioManagementModule::t('am', 'update_audio_library') . $model->af_name;
$this->params['breadcrumbs'][] = ['label' => AudioManagementModule::t('am', 'audio_libraries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content"><A> </A>
                <div class="prompt-list-update">

                    <?= $this->render('_form',
                        [
                            'model' => $model,
                        ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
