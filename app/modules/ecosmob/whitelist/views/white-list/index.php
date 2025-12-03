<?php

use app\modules\ecosmob\whitelist\WhiteListModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\whitelist\models\WhiteListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $importModel app\modules\ecosmob\whitelist\models\WhiteList */

$this->title = WhiteListModule::t('wl', 'white_list');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions']; ?>
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="row">
                <div class="col s12">
                    <div class="profile-contain">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12 search-filter">
                                    <?= $this->render('import', ['importModel' => $importModel]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php Pjax::begin(['id' => 'white-list-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                    <div class="header-title">
                                                        <?= $this->title ?>
                                                    </div>
                                                    <div class="card-header-btns">
                                                        <?php if (in_array('/whitelist/white-list/create', $permissions)) { ?>
                                                                <?= Html::a(WhiteListModule::t('wl', 'add_new'), ['create'], [
                                                                    'id' => 'hov',
                                                                    'data-pjax' => 0,
                                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                ]) ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'white-list-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#white-list-search-form'),
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
                                                                'template' => '{update}{delete}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/whitelist/white-list/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons color-orange">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app', 'update')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/whitelist/white-list/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url,
                                                                                [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => Yii::t('app', 'delete')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'wl_number',
                                                                'enableSorting' => True,
                                                                'headerOptions' => ['class' => 'center', /*'style' => 'width:30%'*/],
                                                                'contentOptions' => ['class' => 'center', /*'style' => 'width:30%'*/],
                                                            ],
                                                            [
                                                                'attribute' => 'wl_description',
                                                                'enableSorting' => True,
                                                                'headerOptions' => ['class' => 'center', /*'style' => 'width:60%'*/],
                                                                'contentOptions' => ['class' => 'center', /*'style' => 'width:60%'*/],
                                                                'value' => function ($model) {
                                                                    return strlen($model->wl_description) > 20 ? substr($model->wl_description, 0, 20) . "..." : (empty($model->wl_description) ? '-' : $model->wl_description);
                                                                }
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
