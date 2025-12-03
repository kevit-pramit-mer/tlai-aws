<?php

use app\modules\ecosmob\blacklistnumberdetails\BlacklistNumberDetailsModule;
use app\modules\ecosmob\blacklistnumberdetails\models\Cdr;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\blacklistnumberdetails\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = BlacklistNumberDetailsModule::t('cdr', 'cdr_reports');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$allColumns = Cdr::allColumns();
?>
<?php Pjax::begin(['id' => 'blacklist-detail-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                  <!-- --><?php /*echo Html::a(BlacklistNumberDetailsModule::t('cdr', 'export'),
                                                        ['export'],
                                                        [
                                                            'id' => 'hov',
                                                            'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                            'data-pjax' => 0,
                                                        ])
                                                    */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= BlacklistNumberDetailsModule::t('cdr', 'Export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo Html::beginForm(['bulk-data'], 'post', array('id' => 'data_form'));
                                                echo GridView::widget([
                                                    'id' => 'cdr-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#cdr-search-form'),
                                                    'showOnEmpty' => true,
                                                    'pager' => [
                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'options' => [
                                                        'tag' => false,
                                                    ],
                                                    'columns' => $allColumns,
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                                echo Html::endForm();
                                            } catch (Exception $e) {
                                            }
                                            ?>
                                        </div>
                                        <div class="empty materialize-red-text">
                                            <?= Yii::t('app', 'export_limit_note') ?>
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
        $('.blacklist-cdr').addClass("active");
        //$('.extension-child').removeClass("active");
        $('.main-cdr').removeClass("active");
    });
</script>

<?php
$this->registerJs("
   $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/blacklistnumberdetails/cdr/export'])."';
        }
    });");
?>












































