<?php

use app\modules\ecosmob\breaks\BreaksModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\breaks\models\Breaks */

$this->title = BreaksModule::t('breaks', 'create_break');
$this->params['breadcrumbs'][] = ['label' => BreaksModule::t('breaks', 'break_management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = BreaksModule::t('breaks', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="breaks-create">

                    <?= $this->render('_form',
                        [
                            'model' => $model,
                        ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>