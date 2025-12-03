<?php

use yii2mod\rbac\models\AuthItemModel;

/* @var $this yii\web\View */
/* @var $model AuthItemModel */

$labels = $this->context->getLabels();
$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<div class="auth-item-create">
    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
