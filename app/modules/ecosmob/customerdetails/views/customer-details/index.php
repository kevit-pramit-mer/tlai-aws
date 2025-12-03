<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\customerdetails\assets\CustomerDetailsAsset;
use app\modules\ecosmob\customerdetails\CustomerDetailsModule;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\customerdetails\models\CampaignMappingUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $extensionInformation */
/* @var $campaignList */

$this->title = CustomerDetailsModule::t('customerdetails', 'customer_details');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
CustomerDetailsAsset::register($this);
?>
<!-- /container -->
<script type="text/javascript">
    var extensionNumber = '<?php echo $extensionInformation['em_extension_number']; ?>'
    var extensionPassword = '<?php echo $extensionInformation['em_password']; ?>'
</script>
<?php Pjax::begin(['id' => 'customer-detail-index', 'timeout' => 0, 'enablePushState' => false]); ?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model' => $searchModel, 'campaignList' => $campaignList]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                            </div>
                                            <?= GridView::widget([
                                                'id' => 'campaign-mapping-user-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#campaign-mapping-user-search-form'),
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                    'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                    'maxButtonCount' => 5,
                                                ],
                                                'options' => [
                                                    'tag' => false,
                                                ],
                                                'columns' => [
                                                    ['class' => 'yii\grid\SerialColumn'],
                                                    /*        [
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
                                                            ],*/

                                                    [
                                                        'attribute' => 'lg_first_name',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if (!empty($model->lg_first_name)) {

                                                                return $model->lg_first_name;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }

                                                    ],
                                                    [
                                                        'attribute' => 'lg_last_name',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if (!empty($model->lg_last_name)) {

                                                                return $model->lg_last_name;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'lg_contact_number',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if (!empty($model->lg_contact_number)) {

                                                                return $model->lg_contact_number;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }

                                                    ],
                                                    [
                                                        'attribute' => 'lg_email_id',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if (!empty($model->lg_email_id)) {

                                                                return $model->lg_email_id;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'lg_address',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if (!empty($model->lg_address)) {

                                                                return $model->lg_address;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'campaign_id',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'label' => Yii::t('app', 'campaign'),
                                                        'value' => function ($model) {
                                                            $campaignList = [];
                                                            $supervisorCamp = CampaignMappingUser::find()
                                                                ->where(['supervisor_id' => Yii::$app->user->id])
                                                                ->all();
                                                            foreach ($supervisorCamp as $supervisorCamps) {
                                                                $campaignList[] = $supervisorCamps['campaign_id'];
                                                            }

                                                            $campaign = Campaign::find()->select(['cmp_name'])
                                                                ->Where(['cmp_id' => $campaignList])
                                                                ->andWhere(['cmp_lead_group' => $model->ld_id])
                                                                ->asArray()->all();

                                                            foreach ($campaign as $value) {
                                                                $camp[] = $value['cmp_name'];
                                                            }
                                                            if (!empty($camp))
                                                                return implode(', ', $camp);
                                                            else
                                                                return '-';
                                                        },
                                                        'enableSorting' => TRUE,
                                                    ],
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline'
                                                ],
                                            ]); ?>
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
