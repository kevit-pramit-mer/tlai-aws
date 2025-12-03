<?php

use yii\helpers\Html;
use app\modules\ecosmob\agent\AgentModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agent\models\Agent */

$this->title = AgentModule::t( 'agent', 'create_agent' );
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
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
                <div class="agent-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
