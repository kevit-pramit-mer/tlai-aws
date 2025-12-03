<?php

use app\modules\ecosmob\user\assets\UserAsset;
use app\modules\ecosmob\user\UserModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $roles */

$this->title = UserModule::t('usr', 'usr');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

UserAsset::register($this);
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'user-index', 'timeout' => 0, 'enablePushState' => false]); ?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model' => $searchModel, 'roles' => $roles]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                       <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                    <?php if (in_array('/user/user/create', $permissions)) { ?>
                                                        <?= Html::a(UserModule::t('usr', 'add_new'), ['create'], [
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light  darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                    <?php } ?>
                                                    <?php /*if (in_array('/user/user/trashed', $permissions)) { */?><!--
                                                        <?php /*= Html::a(UserModule::t('usr', 'trash'), ['trashed'], [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) */?>
                                                    --><?php /*} */?>
                                                </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget(['id' => 'user-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#user-search-form'),
                                                    'showOnEmpty' => true,
                                                    'pager' => [
                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                        'maxButtonCount' => 5
                                                    ],
                                                    'options' => ['tag' => false,],
                                                    'columns' => [['class' => 'yii\grid\ActionColumn',
                                                        'template' => '{update}{delete}',
                                                        'header' => Yii::t('app', 'action'),
                                                        'headerOptions' => ['class' => 'center width-10'],
                                                        'contentOptions' => ['class' => 'center width-10'],
                                                        'buttons' => ['update' => function ($url, $model) use ($permissions) {
                                                            if (in_array('/user/user/update', $permissions)) {
                                                                return (1 ? Html::a('<i class="material-icons color-orange">edit</i>', $url, ['title' => Yii::t('app', 'update'),]) : '');
                                                            }
                                                        },
                                                            'delete' => function ($url, $model) use ($permissions) {
                                                                if (in_array('/user/user/delete', $permissions)) {
                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>', $url, ['class' => 'ml-5',
                                                                        'data-pjax' => 0,
                                                                        'style' => 'color:#FF4B56',
                                                                        'data-content' => UserModule::t('usr', 'move_trash'),
                                                                        'data-confirm' => UserModule::t('usr', 'trash_confirm'),
                                                                        'data-method' => 'post',
                                                                        'title' => UserModule::t('usr', 'move_trash'),]) : '');
                                                                }
                                                            },]],

                                                        ['attribute' => 'adm_status',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'format' => 'raw',
                                                            'enableSorting' => true,
                                                            'value' => function ($model) {
                                                                if ($model->adm_status == 1) {
                                                                    return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                } else {
                                                                    return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }

                                                            },],

                                                        ['attribute' => 'adm_firstname',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => true,
                                                            'contentOptions' => ['class' => 'text-center'],],
                                                        ['attribute' => 'adm_lastname',
                                                            'enableSorting' => true,
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],],
                                                        ['attribute' => 'adm_email',
                                                            'enableSorting' => true,
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],],
                                                        ['attribute' => 'adm_username',
                                                            'enableSorting' => true,
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],],
                                                        ['attribute' => 'adm_is_admin',
                                                            'enableSorting' => true,
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],],],
                                                    'tableOptions' => ['class' => 'display dataTable dtr-inline',
                                                    ],]);
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
<script>
    $(document).ready(function () {
        $('.user-main').addClass("active");
        $('.super-child').removeClass("active");
        //$('.main-cdr').removeClass("active");
    });
</script>
