<?php

use yii\helpers\Html;
use app\modules\ecosmob\callrecordings\CallRecordingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callrecordings\models\CallRecordings */

$this->title = CallRecordingsModule::t('app', 'crt_call_record') . $model->cr_id;
$this->params['breadcrumbs'][] = ['label' => CallRecordingsModule::t('app', 'call_record'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cr_id, 'url' => ['index']];
$this->params['breadcrumbs'][] = CallRecordingsModule::t('app', 'update');
$this->params['pageHead'] = $this->title;
?>
</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="call-recordings-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>