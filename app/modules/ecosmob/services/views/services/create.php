<?php

use yii\helpers\Html;
use app\modules\ecosmob\services\ServicesModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\services\models\Services */

$this->title = ServicesModule::t('services', 'create_services');
$this->params['breadcrumbs'][] = ['label' => ServicesModule::t('services', 'services'), 'url' => ['index']];
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
                <div class="services-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>