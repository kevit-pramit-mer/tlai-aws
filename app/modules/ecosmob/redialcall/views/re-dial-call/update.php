<?php

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\redialcall\models\ReDialCall */

$this->title = Yii::t('app', 'Update Re Dial Call: ' . $model->ld_id, [
    'nameAttribute' => '' . $model->ld_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Re Dial Calls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ld_id, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="re-dial-call-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>