<?php

use app\modules\ecosmob\queue\QueueModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queue\models\QueueMaster */
/* @var $agents */
/* @var $availableAgentsUpdate */

$this->title = QueueModule::t('queue', 'update_queue_master') . $model->qm_name;
$this->params['breadcrumbs'][] = ['label' => QueueModule::t('queue', 'q'), 'url' => ['index']];
$this->params['breadcrumbs'][] = QueueModule::t('queue', 'update');
$this->params['pageHead'] = $this->title;
?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="queue-master-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'agents' => $agents,
                        'availableAgentsUpdate' => $availableAgentsUpdate,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>