<?php

/* @var $this yii\web\View */
/* @var $model AuthItemModel */

use yii2mod\rbac\models\AuthItemModel;

$context = $this->context;
$labels = $this->context->getLabels();
$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
$this->params['pageHead'] = $this->title;
?>
<div class="auth-item-update">
    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
