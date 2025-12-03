<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel yii2mod\rbac\models\search\BizRuleSearch */

$this->title = Yii::t('yii2mod.rbac', 'Rules');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$perPage = Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 5;
Yii::$app->session->set('per-page-result', $perPage);
?>
<div class="role-index">
    <?php Pjax::begin(['timeout' => 5000]); ?>
    <div class="card">
        <div class="card-header card-custom">
            <h6>
                <span class="fa fa-user"></span><?= ' ' . Yii::t('yii2mod.rbac', $this->title) ?>
                <?= Html::a(
                    Yii::t('app', 'add_new'), ['create'],
                    [
                        'id' => 'hov',
                        'data-pjax' => 0,
                        'class' => 'btn float-xs-right btn-round-right btn-primary btn-sm hvr-icon-forward',
                    ]) ?>
            </h6>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card card-default">
                <div class="card-header">
                    <div class="basic_bootstrap_tbl custom-toolbar">
                        <?php echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => Yii::$app->layoutHelper->get_layout_str('#assignment-search-form'),
                            'showOnEmpty' => true,
                            'options' => [
                                'class' => 'grid-view-color text-center',
                            ],
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'label' => Yii::t('yii2mod.rbac', 'Name'),
                                ],
                                [
                                    'header' => Yii::t('yii2mod.rbac', 'Action'),
                                    'class' => 'yii\grid\ActionColumn',
                                ],
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
