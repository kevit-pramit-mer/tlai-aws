<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\ecosmob\callrecordings\CallRecordingsModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\callrecordings\models\CallRecordingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CallRecordingsModule::t('app', 'call_record');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">


                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php Pjax::begin([
                                                'enablePushState' => false,
                                                'id' => 'pjax-call-recordings'
                                            ]); ?>
                                            <?=
                                            GridView::widget([
                                                'id' => 'call-recordings-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'filterModel' => $searchModel,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#call-recordings-search-form'),
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                    'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                    'maxButtonCount' => 5,
                                                ],
                                                'options' => [
                                                    'tag' => false,
                                                ],
                                                'columns' => [
                                                    [
                                                        'attribute' => 'name',
                                                        'format' => 'raw',
                                                        'value' => function ($model) {
                                                            $sourcePath = Url::to('@web' . Yii::$app->params['CAL_RECORDING_PATH'] . $model['name']);
                                                            $audio_file = "<audio controls>
                                                                <source src='" . $sourcePath . "' type='audio/mp3' />
                                                               </audio>";

                                                            return $audio_file;
                                                        },
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'created_date',
                                                        'value' => function ($model) {
                                                            return $model['created_date'];
                                                        }
                                                    ]
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline',
                                                    
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

