<?php

use app\modules\ecosmob\cdr\models\InboundCdr;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\cdr\CdrModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\fraudcalldetectionreport\models\InboundCdr */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FraudCallDetectionReportModule::t('cdr', 'inbound_cdr');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$allColumns = InboundCdr::allColumns();
if (Yii::$app->user->identity->user_type == 'service_provider') {
    unset($allColumns['sp_name']);
}
if (Yii::$app->user->identity->user_type == 'customer') {
    unset($allColumns['sp_name']);
    unset($allColumns['user_name']);
}
?>

<?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax-inboundcdr']); ?>
<div class="inboundcdr-index"
     id="inboundcdr-index">
    <?php
    if (Yii::$app->user->identity->user_type == 'customer') {
        echo $this->render('_search', ['model' => $searchModel]);
    } else {
        echo $this->render('_search', ['model' => $searchModel, 'DdData' => $DdData]);
    } ?>
    <div class="hseparator"></div>
    <div class="card">
        <div class="card-header card-custom">
            <h6>
                <span class="fa fa-plus-circle"></span>
                <?= Html::encode($this->title) ?>
                <?php
                if (!empty($dataProvider->models)) { ?>
                    <?php echo Html::a( 'Export',
                        [ 'export' ],
                        [
                        'id' => 'hov',
                        'class' => 'btn float-xs-right btn-round-left btn-success btn-sm',
                        'data-pjax' => 0,
                        ] ) ?><?php } ?>
            </h6>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-default">
                <div class="card-header">
                    <div class="basic_bootstrap_tbl custom-toolbar">
                        <?= GridView::widget([
                            'id'           => 'inboundcdr-grid-index',
                            'dataProvider' => $dataProvider,
                            'showOnEmpty'  => true,
                            'layout'       => Yii::$app->layoutHelper->get_layout_str('#inboundcdr-search-form'),
                            'options'      => [
                                'class' => 'grid-view-color text-center',
                            ],
                            'columns'      => $allColumns,
                            'tableOptions' => [
                                'class' => 'table table-striped display nowrap table-bordered sorting_asc',
                                'id' => 'table1',
                                'data-plugin' => 'bootstraptable',
                                'data-height' => Yii::$app->session->get('per-page-result') == 5 ? '320' : '480',
                                'data-toolbar' => '#toolbar',
                                'data-show-columns' => 'true',
                                'data-icons-prefix' => 'fa',
                                'data-mobile-responsive' => 'false',
                                'data-search' => 'true',
                                'data-cookie' => 'true',
                                'data-cookie-id-table' => 'inboundCdrIndex',
                            ],
                        ]); ?>
                    </div>
                    <?php Pjax::end(); ?>                </div>
            </div>
        </div>
    </div>
</div>
