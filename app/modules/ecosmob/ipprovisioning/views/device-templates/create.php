<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ipprovisioning\models\DeviceTemplates */

$this->title = 'Create Device Templates';
$this->params['breadcrumbs'][] = ['label' => 'Device Templates', 'url' => ['index']];
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
                <div class="device-templates-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>