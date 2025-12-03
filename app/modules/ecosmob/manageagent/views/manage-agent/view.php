<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\manageagent\models\ManageAgent */

$this->title = $model->adm_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Agents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manage-agent-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->adm_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->adm_id], [
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
            'adm_id',
            'adm_firstname',
            'adm_lastname',
            'adm_username',
            'adm_email:email',
            'adm_password',
            'adm_password_hash',
            'adm_contact',
            'adm_is_admin',
            'adm_status',
            'adm_timezone_id:datetime',
            'adm_last_login',
        ],
    ]) ?>

</div>
