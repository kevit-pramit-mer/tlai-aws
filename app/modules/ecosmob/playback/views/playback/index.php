<?php

use app\modules\ecosmob\playback\PlaybackModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\playback\models\PlaybackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'playback');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
if (in_array('/playback/playback/create', $permissions)) { ?>
    <div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
        <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
        <?= Html::a(PlaybackModule::t('pb', 'add_new'),
            ['create'],
            [
                'id' => 'hov',
                'data-pjax' => 0,
                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
            ]) ?>
    </div>
<?php } ?>
<?php Pjax::begin(['id' => 'playback-index', 'timeout' => 0, 'enablePushState' => false]); ?>
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="row">
                <div class="col s12">
                    <div class="profile-contain">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12 search-filter">
                                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                                </div>
                                <div class="col s12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'playback-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#playback-search-form'),
                                                        'showOnEmpty' => true,
                                                        'pager' => [
                                                            'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                            'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                            'maxButtonCount' => 5,
                                                        ],
                                                        'options' => [
                                                            'tag' => FALSE,
                                                        ],
                                                        'columns' => [
                                                            [
                                                                'class' => 'yii\grid\ActionColumn',
                                                                'template' => '{delete}',
                                                                'header' => Yii::t('app',
                                                                    'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'delete' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/playback/playback/delete', $permissions)) {
                                                                            return (1
                                                                                ? Html::a('<i class="material-icons">delete</i>',
                                                                                    $url,
                                                                                    [
                                                                                        'class' => 'ml-5',
                                                                                        'data-pjax' => 0,
                                                                                        'style' => 'color:#FF4B56',
                                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                        'data-method' => 'post',
                                                                                        'title' => Yii::t('app', 'delete'),
                                                                                    ]) : '');
                                                                        }
                                                                    },
                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'pb_name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting'=> TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'pb_language',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting'=> TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'pb_file',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => False,
                                                                'format' => 'raw',
                                                                'value' => function ($model) {
                                                                    $extension = pathinfo($model->pb_file, PATHINFO_EXTENSION);
                                                                    $sourcePath = Url::to('@web' . '/media/' . $GLOBALS['tenantID'] . '/playback/' . $model->pb_file);
                                                                    if ($extension == "mp3") {
                                                                        return '<audio controls class="audiofile">
                                                                        <source src="' . $sourcePath . '" type="audio/mpeg">
                                                                    </audio>';
                                                                    } else {
                                                                        return '<audio controls class="audiofile">
                                                                        <source src="' . $sourcePath . '" type="audio/wav">
                                                                    </audio>';
                                                                    }
                                                                },
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline',

                                                        ],
                                                    ]);
                                                } catch (Exception $e) {
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
<?php Pjax::end();
