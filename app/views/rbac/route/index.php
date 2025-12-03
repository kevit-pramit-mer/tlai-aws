<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii2mod\rbac\RbacRouteAsset;

RbacRouteAsset::register($this);

/* @var $this yii\web\View */
/* @var $routes array */

$this->title = Yii::t('yii2mod.rbac', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$perPage = Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 5;
Yii::$app->session->set('per-page-result', $perPage);
?>
<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?= Html::a('Refresh', ['refresh'], [
        'id' => 'btn-refresh',
        'data-pjax' => 0,
        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
    ]) ?>
</div>
</div>
</div>
</div>

<div class="row">
    <div class="col s12">
        <div class="card card-default">
            <div class="card-header">
                <form id="assignment-search-form"></form>
                <div class="basic_bootstrap_tbl custom-toolbar">
                    <?php echo $this->render('../_dualListBox', [
                        'opts' => Json::htmlEncode([
                            'items' => $routes,
                        ]),
                        'assignUrl' => ['assign'],
                        'removeUrl' => ['remove'],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
