<?php

use app\modules\ecosmob\campaign\CampaignModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\campaign\models\Campaign */
/* @var $timeZoneList */
/* @var $queueList */
/* @var $leadGroupList */
/* @var $dispositionList */
/* @var $scriptList */
/* @var $trunkList */
/* @var $availableSupervisors */
/* @var $availableAgents */

$this->title = CampaignModule::t('campaign', 'create');
$this->params['breadcrumbs'][] = ['label' => CampaignModule::t('campaign', 'campaign_management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="campaign-create">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'availableSupervisors' => $availableSupervisors,
                        'availableAgents' => $availableAgents,
                        'timeZoneList' => $timeZoneList,
                        'queueList' => $queueList,
                        'dispositionList' => $dispositionList,
                        'leadGroupList' => $leadGroupList,
                        'trunkList' => $trunkList,
                        'scriptList' => $scriptList,
                        /*'timeConditionList' => $timeConditionList,*/
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>