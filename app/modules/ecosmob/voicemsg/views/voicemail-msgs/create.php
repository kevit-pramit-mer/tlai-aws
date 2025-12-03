<?php


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\voicemsg\models\VoicemailMsgs */

$this->title = Yii::t('app', 'Create Voicemail Msgs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voicemail Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="voicemail-msgs-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>