<?php

use app\modules\ecosmob\jobs\JobsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\jobs\models\Job */
/* @var $timeZoneList */
/* @var $campaignList */
/* @var $timeConditionList */

$this->title = JobsModule::t('jobs', 'update_job') . $model->job_name;
$this->params['breadcrumbs'][] = ['label' => JobsModule::t('jobs', 'job'), 'url' => ['index']];
$this->params['breadcrumbs'][] = JobsModule::t('jobs', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="job-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'timeZoneList' => $timeZoneList,
                        'timeConditionList' => $timeConditionList,
                        'campaignList' => $campaignList,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>