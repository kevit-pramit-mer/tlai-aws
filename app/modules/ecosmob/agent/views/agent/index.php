<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\ecosmob\accessrestriction\AgentModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\agent\models\AgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Agents';
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead']      = $this->title;
?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render( '_search', [ 'model' => $searchModel ] ); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?= Html::a('Export', ['/agentscallreport/agents-call-report/export'], [
                                                    'id'=>'hov',
                                                    'data-pjax'=>0,
                                                    'class'=>'exportbutton lead_group btn waves-effect waves-light  darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget( [
                                                    'id'           => 'agent-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout'       => Yii::$app->layoutHelper->get_layout_str( '#agent-search-form' ),
                                                    'showOnEmpty'  => true,
                                                    'pager'        => [
                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">'.Yii::t('app','previous').'</a>',
                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">'.Yii::t('app','next').'</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'options'      => [
                                                        'tag' => FALSE,
                                                    ],
                                                    'columns'      => [
                                                        [
                                                            'class'          => 'yii\grid\ActionColumn',
                                                            'template'       => '{update}{delete}',
                                                            'header'         => Yii::t( 'app', 'action' ),
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                            'buttons'        => [
                                                                'update' => function ( $url, $model ) {
                                                                    return ( 1 ? Html::a( '<i class="material-icons">edit</i>',
                                                                        $url,
                                                                        [
                                                                            'style' => '',
                                                                            'title' => Yii::t( 'app', 'update' ),
                                                                        ] ) : '' );
                                                                },
                                                                'delete' => function ( $url, $model ) {
                                                                    return ( 1 ? Html::a( '<i class="material-icons">delete</i>',
                                                                        $url,
                                                                        [

                                                                            'class'        => 'ml-5',
                                                                            'data-pjax'    => 0,
                                                                            'style'        => 'color:#FF4B56',
                                                                            'data-confirm' => Yii::t( 'app', 'delete_confirm' ),
                                                                            'data-method'  => 'post',
                                                                            'title'        => Yii::t( 'app', 'delete' ),
                                                                        ] ) : '' );
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute'      => 'name',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'system',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'uuid',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'type',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'status',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'state',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],

                                                        [
                                                            'attribute'      => 'max_no_answer',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'wrap_up_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'reject_delay_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'busy_delay_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'no_answer_delay_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'last_bridge_start',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'last_bridge_end',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'last_offered_call',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'last_status_change',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'no_answer_count',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'calls_answered',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'talk_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'ready_time',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],
                                                        [
                                                            'attribute'      => 'reject_call_count',
                                                            'headerOptions'  => [ 'class' => 'center' ],
                                                            'contentOptions' => [ 'class' => 'center' ],
                                                        ],

                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline'
                                                    ],
                                                ] );
                                            }
                                            catch ( Exception $e ) {
                                            } ?>

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

