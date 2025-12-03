<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\emailtemplate\models\EmailTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = EmailTemplateModule::t( 'emailtemplate','email_tmp' );
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead']      = $this->title;
?>

<?php Pjax::begin( [ 'enablePushState' => FALSE, 'id' => 'pjax-email-template' ] ); ?>
<div class="email-template-index"
     id="email-template-index">
    <?php echo $this->render( '_search', [ 'model' => $searchModel ] ); ?>
    <div class="hseparator"></div>
    <div class="card">
        <div class="card-header card-custom">
            <h6>
                <span class="fa fa-envelope"></span>
                <?= Html::encode( $this->title ) ?>
                <?= Html::a( 'Create',
                    [ 'create' ],
                    [
                        'id'        => 'hov',
                        'data-pjax' => 0,
                        'class'     => 'btn float-xs-right btn-round-right btn-primary btn-sm hvr-icon-forward',
                    ] ) ?>
                <label class="btn-primary pull-right mr-1 help-btn-p ">
                    <i class="icon fa fa-question-circle fa-lg" data-toggle="popover"
                       data-trigger="hover" data-placement="left"
                       data-content="<?= EmailTemplateModule::t( 'emailtemplate', 'emailtemplate_notes' ) ?>"></i>
                </label>
            </h6>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-default">
                <div class="card-header">
                    <div class="basic_bootstrap_tbl custom-toolbar">
                        <?php try {
                            echo GridView::widget( [
                                'id'           => 'email-template-grid-index', // TODO : Add Grid Widget ID
                                'dataProvider' => $dataProvider,
                                'layout'       => Yii::$app->layoutHelper->get_layout_str( '#email-template-search-form' ),
                                'showOnEmpty'  => true,
                                'options'      => [
                                    'class' => 'grid-view-color text-center',
                                ],
                                'columns'      => [
                                    [
                                        'class'          => 'yii\grid\ActionColumn',
                                        'template'       => '{update}',
                                        'header'         => EmailTemplateModule::t( 'emailtemplate', 'action' ),
                                        'headerOptions'  => [ 'class' => 'text-center' ],
                                        'contentOptions' => [ 'class' => 'text-center btn-space' ],
                                        'buttons'        => [
                                            'update' => function ( $url, $model ) {
                                                return ( 1 ? Html::a( '<i class="fa fa-pencil"></i>',
                                                    $url,
                                                    [
                                                        'data-toggle'    => 'popover',
                                                        'container'      => "body",
                                                        'data-placement' => 'top',
                                                        'data-trigger'   => "hover",
                                                        'data-content'   => EmailTemplateModule::t( 'emailtemplate', 'edit' ),
                                                        'data-pjax'      => 0,
                                                        'class'          => 'btn btn-primary btn-sm',
                                                    ] ) : '' );
                                            },
                                        ],
                                    ],
                                    [
                                        'attribute'      => 'key',
                                        'headerOptions'  => [ 'class' => 'text-center' ],
                                        'contentOptions' => [ 'class' => 'text-center' ],
                                    ],
                                    [
                                        'attribute'      => 'subject',
                                        'headerOptions'  => [ 'class' => 'text-center' ],
                                        'contentOptions' => [ 'class' => 'text-center' ],
                                    ],
                                ],
                                'tableOptions' => [
                                    'class'                   => 'table table-striped display nowrap table-bordered sorting_asc',
                                    'id'                      => 'table1',
                                    'data-plugin'             => 'bootstraptable',
                                    'data-height'             => Yii::$app->session->get( 'per-page-result' ) == 5 ? '320' : '480',
                                    'data-search'             => 'true',
                                    'data-search-placeholder' => Yii::t( 'app', 'quick_search' ),
                                    'data-icons-prefix'       => 'fa',
                                    'data-mobile-responsive'  => 'false',
                                    'data-show-columns'       => 'true',
                                    'data-cookie'             => 'true',
                                    'data-cookie-id-table'    => 'EmailTemplateIndex',
                                ],
                            ] );
                        }
                        catch ( Exception $e ) {
                        } ?>
                    </div>
                    <?php Pjax::end(); ?>                </div>
            </div>
        </div>
    </div>
</div>
