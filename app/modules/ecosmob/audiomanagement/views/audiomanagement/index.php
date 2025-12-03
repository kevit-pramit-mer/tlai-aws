<?php

use app\modules\ecosmob\audiomanagement\AudioManagementModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\ecosmob\audiomanagement\models\AudioManagementSearch */

$this->title = AudioManagementModule::t('am', 'aud_lib');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'audio-library-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?php if (in_array('/audiomanagement/audiomanagement/create', $permissions)) { ?>
                                                    <?= Html::a(AudioManagementModule::t('am', 'add_new'),
                                                        ['create'],
                                                        [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                <?php } ?>
                                            </div> 
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'audio-management-grid-index',
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#audio-management-search-form'),
                                                    'showOnEmpty' => TRUE,
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
                                                            'template' => '{update}{delete}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function ($url, $model) use ($permissions) {
                                                                    if (in_array('/audiomanagement/audiomanagement/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'update'),
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url, $model) use ($permissions) {
                                                                    if (in_array('/audiomanagement/audiomanagement/delete', $permissions)) {
                                                                        $candelete = $model->canDelete($model->af_id);
                                                                        if ($candelete) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url,
                                                                                [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'title' => Yii::t('app', 'delete'),
                                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                ]) : '');
                                                                        } else {
                                                                            $canNotDelete = AudioManagementModule::t('app', 'can_not_delete');
                                                                            return '<a disabled name="login-button" class="ml-5 opacity5" title="' . $canNotDelete . '"><i class="material-icons">delete</i></a>';
                                                                        }
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute' => 'af_name',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                        ],
                                                        [
                                                            'attribute' => 'af_type',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                        ],
                                                        [
                                                            'attribute' => 'af_language',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                if ($model->af_language == 'English') {
                                                                    return Yii::t('app', 'english');
                                                                } else {
                                                                    return Yii::t('app', 'spanish');
                                                                }
//
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'af_file',
                                                            'enableSorting' => False,
                                                            'headerOptions' => ['class' => 'audio-text-center'],
                                                            'contentOptions' => ['class' => 'text-center audio-added'],
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                $extension = pathinfo($model->af_file, PATHINFO_EXTENSION);

                                                                if ($model->af_type == 'Recording') {
                                                                    $sourcePath = Url::to('@web' . '/media/recordings/'.$model->af_file);
                                                                } else {
                                                                    $sourcePath = Url::to('@web' . '/media/audio-libraries/'.$model->af_file);
                                                                }
//                                                                $sourcePath = Url::to( '@web' . '/media/admin/audio-libraries/' . $model->af_file);
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
                                                        'class' => 'display dataTable dtr-inline'
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
<?php Pjax::end(); ?>
