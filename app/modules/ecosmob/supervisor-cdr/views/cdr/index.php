<?php

use app\modules\ecosmob\cdr\models\Cdr;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\cdr\CdrModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\cdr\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title=CdrModule::t('cdr', 'cdr_reports');
$this->params['breadcrumbs'][]=$this->title;
$this->params['pageHead']=$this->title;

$allColumns=Cdr::allColumns();

?>

<!--<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <?php
/*    if (!empty($dataProvider->models)) { */?>
        <?php /*echo Html::a('Export',
            ['export'],
            [
                'id'=>'hov',
                'class'=>'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                'data-pjax'=>0,
            ])
        */?><?php /*} */?>
</div>-->

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model'=>$searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">

                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php /*Pjax::begin([
                                                'enablePushState'=>false,
                                                'id'=>'pjax-phonebook'
                                            ]);*/ ?>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id'=>'cdr-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider'=>$dataProvider,
                                                    'layout'=>Yii::$app->layoutHelper->get_layout_str('#cdr-search-form'),
                                                    'showOnEmpty'=>false,
                                                    'pager'=>[
                                                        'prevPageLabel'=>'<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                        'nextPageLabel'=>'<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'options'=>[
                                                        'tag'=>false,
                                                    ],
                                                    'columns'=>$allColumns,
                                                    'tableOptions'=>[
                                                        'class'=>'display dataTable dtr-inline',
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>














































