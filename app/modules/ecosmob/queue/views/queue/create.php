<?php

use app\modules\ecosmob\queue\QueueModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queue\models\QueueMaster */
/* @var $availableAgents */

$this->title = QueueModule::t('queue', 'create_queue_master');
$this->params['breadcrumbs'][] = ['label' => QueueModule::t('queue', 'q'), 'url' => ['index']];
$this->params['breadcrumbs'][] = QueueModule::t('queue', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="queue-master-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'availableAgents' => $availableAgents,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
