<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding */

$this->title = $model->ef_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension Forwardings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>

<div class="extension-forwarding-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ef_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ef_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ef_extension',
            'ef_unconditional_type',
            'ef_unconditional_num',
            'ef_holiday_type',
            'ef_holiday',
            'ef_holiday_num',
            'ef_weekoff_type',
            'ef_weekoff',
            'ef_weekoff_num',
            'ef_shift_type',
            'ef_shift',
            'ef_shift_num',
            'ef_universal_type',
            'ef_universal_num',
            'ef_no_answer_type',
            'ef_no_answer_num',
            'ef_busy_type',
            'ef_busy_num',
            'ef_unavailable_type',
            'ef_unavailable_num',
        ],
    ]) ?>

</div>
