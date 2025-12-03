<?php

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\systemcode\models\FeatureMaster */

$this->title = Yii::t('app', 'Update Feature Master: ' . $model->feature_id, [
    'nameAttribute' => '' . $model->feature_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feature Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->feature_id, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="feature-master-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>