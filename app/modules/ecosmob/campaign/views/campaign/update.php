<?php

use yii\helpers\Html;
use app\modules\ecosmob\campaign\CampaignModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\campaign\models\Campaign */
/* @var $timeZoneList */
/* @var $queueList */
/* @var $leadGroupList */
/* @var $supervisors */
/* @var $agents */
/* @var $availableSupervisorsUpdate */
/* @var $availableAgentsUpdate */
/* @var $dispositionList */
/* @var $scriptList */
/* @var $trunkList */

$this->title=CampaignModule::t('campaign', 'update_campaign') . $model->cmp_name;
$this->params['breadcrumbs'][]=['label'=>CampaignModule::t('campaign', 'campaign_management'), 'url'=>['index']];
$this->params['breadcrumbs'][]=CampaignModule::t('campaign', 'update');
$this->params['pageHead']=$this->title;
?>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="campaign-update">

                    <?= $this->render('_form', [
                        'model'=>$model,
                        'supervisors'=>$supervisors,
                        'agents' => $agents,
                        'availableSupervisorsUpdate'=>$availableSupervisorsUpdate,
                        'availableAgentsUpdate' => $availableAgentsUpdate,
                        'timeZoneList'=>$timeZoneList,
                        'queueList'=>$queueList,
                        'dispositionList'=>$dispositionList,
                        'leadGroupList'=>$leadGroupList,
                        'trunkList'=>$trunkList,
                        'scriptList'=>$scriptList,
                        /*'timeConditionList' => $timeConditionList,*/
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
