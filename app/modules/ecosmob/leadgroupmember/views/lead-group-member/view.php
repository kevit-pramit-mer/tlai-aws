<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */

$this->title = $model->lg_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lead Group Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lead-group-member-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->lg_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->lg_id], [
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
            'lg_id',
            'lg_first_name',
            'lg_last_name',
            'lg_contact_number',
            'lg_email_id:email',
            'lg_address',
            'lg_alternate_number',
            'lg_pin_code',
            'lg_permanent_address',
        ],
    ]) ?>

</div>
