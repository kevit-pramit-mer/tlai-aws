<?php


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callhistory\models\CampCdr */

$this->title = Yii::t('app', 'Create Camp Cdr');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camp Cdrs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="camp-cdr-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>