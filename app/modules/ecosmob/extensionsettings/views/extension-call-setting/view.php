<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting */

$this->title = $model->ecs_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension Call Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-call-setting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ecs_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ecs_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'em_id',
            'ecs_max_calls',
            'ecs_ring_timeout:datetime',
            'ecs_call_timeout:datetime',
            'ecs_ob_max_timeout:datetime',
            'ecs_auto_recording',
            'ecs_dtmf_type',
            'ecs_video_calling',
            'ecs_bypass_media',
            'ecs_srtp',
            'ecs_force_record',
            'ecs_moh',
            'ecs_audio_codecs',
            'ecs_video_codecs',
            'ecs_dial_out',
            'ecs_forwarding',
            'ecs_voicemail:email',
            'ecs_voicemail_password:email',
            'ecs_fax2mail',
            'ecs_feature_code_pin',
            'ecs_multiple_registeration',
        ],
    ]) ?>

</div>
