<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\activecalls\models\ActiveCalls */

$this->title = Yii::t('app', 'Create Active Calls');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Active Calls'), 'url' => ['index']];
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
                <div class="active-calls-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>