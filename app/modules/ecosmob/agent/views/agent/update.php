<?php

use app\modules\ecosmob\accessrestriction\AgentModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agent\models\Agent */

$this->title = \app\modules\ecosmob\agent\AgentModule::t('agent', 'update_agent') . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$this->params['pageHead'] = $this->title;
?>
</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="agent-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
