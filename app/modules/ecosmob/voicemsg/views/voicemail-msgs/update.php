<?php

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\voicemsg\models\VoicemailMsgs */

$this->title = Yii::t('app', 'Update Voicemail Msgs: ' . $model->uuid, [
    'nameAttribute' => '' . $model->uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voicemail Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->uuid, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="voicemail-msgs-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>