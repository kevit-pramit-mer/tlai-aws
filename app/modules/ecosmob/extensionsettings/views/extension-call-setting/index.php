<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extensionsettings\models\ExtensionCallSettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title=ExtensionSettingsModule::t('extensionsettings', 'ext_call_set');
$this->params['breadcrumbs'][]=$this->title;
$this->params['pageHead']=$this->title;
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?= Html::a('Add New', ['create'], [
        'id'=>'hov',
        'data-pjax'=>0,
        'class'=>'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
    ]) ?>
</div>

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

                                            <?php Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-extension-call-setting']); ?>
                                            <?= GridView::widget([
                                                'id'=>'extension-call-setting-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider'=>$dataProvider,
                                                'layout'=>Yii::$app->layoutHelper->get_layout_str('#extension-call-setting-search-form'),
                                                'showOnEmpty'=>false,
                                                'pager'=>[
                                                    'prevPageLabel'=>'<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                    'nextPageLabel'=>'<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                    'maxButtonCount' => 5,
                                                ],
                                                'options'=>[
                                                    'tag'=>false,
                                                ],
                                                'columns'=>[
                                                    [
                                                        'class'=>'yii\grid\ActionColumn',
                                                        'template'=>'{update}{delete}',
                                                        'header'=>Yii::t('app', 'action'),
                                                        'headerOptions'=>['class'=>'center width-10'],
                                                        'contentOptions'=>['class'=>'center width-10'],
                                                        'buttons'=>[
                                                            'update'=>function ($url, $model) {
                                                                return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                    'style'=>'',
                                                                    'title'=>Yii::t('app', 'update'),
                                                                ]) : '');
                                                            },
                                                            'delete'=>function ($url, $model) {
                                                                return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                    'class'=>'ml-5',
                                                                    'data-pjax'=>0,
                                                                    'style'=>'color:#FF4B56',
                                                                    'data-confirm'=>Yii::t('app', 'delete_confirm'),
                                                                    'data-method'=>'post',
                                                                    'title'=>Yii::t('app', 'delete'),
                                                                ]) : '');
                                                            },
                                                        ]
                                                    ],

                                                    [
                                                        'attribute'=>'em_id',
                                                        'headerOptions'=>['class'=>'text-center'],
                                                        'contentOptions'=>['class'=>'text-center'],
                                                    ],
                                                    [
                                                        'attribute'=>'ecs_max_calls',
                                                        'headerOptions'=>['class'=>'text-center'],
                                                        'contentOptions'=>['class'=>'text-center'],
                                                    ],
                                                    [
                                                        'attribute'=>'ecs_ring_timeout',
                                                        'headerOptions'=>['class'=>'text-center'],
                                                        'contentOptions'=>['class'=>'text-center'],
                                                    ],
                                                    [
                                                        'attribute'=>'ecs_call_timeout',
                                                        'headerOptions'=>['class'=>'text-center'],
                                                        'contentOptions'=>['class'=>'text-center'],
                                                    ],
                                                    /*[
                                        'attribute'=>'ecs_ob_max_timeout',
                                        'headerOptions'=>['class' => 'text-center'],
                                        'contentOptions' => ['class' => 'text-center'],
                                        ],
                                        */            /*[
'attribute'=>'ecs_auto_recording',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_dtmf_type',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_video_calling',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_bypass_media',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_srtp',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_force_record',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_moh',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_audio_codecs',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_video_codecs',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_dial_out',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_forwarding',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_voicemail:email',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_voicemail_password:email',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_fax2mail',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_feature_code_pin',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/            /*[
'attribute'=>'ecs_multiple_registeration',
'headerOptions'=>['class' => 'text-center'],
'contentOptions' => ['class' => 'text-center'],
],
*/],
                                                'tableOptions'=>[
                                                    'class'=>'display dataTable dtr-inline',
                                                    
                                                ],
                                            ]); ?>

                                            <?php Pjax::end(); ?>
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

