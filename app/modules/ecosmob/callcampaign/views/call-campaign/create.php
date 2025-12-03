<?php

use yii\helpers\Html;
use app\modules\ecosmob\callcampaign\CallCampaignModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callcampaign\models\CallCampaign */

$this->title = CallCampaignModule::t('app', 'create_call_camp');
$this->params['breadcrumbs'][] = ['label' => CallCampaignModule::t('app', 'call_camp'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="call-campaign-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>