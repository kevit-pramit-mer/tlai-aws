<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\manageagent\ManageAgentModule;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CommonHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\manageagent\models\ManageAgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ManageAgentModule::t('manageagent', 'manage_agent');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'manage-agent-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                </div>
                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'manage-agent-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#manage-agent-search-form'),
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
                                                            [
                                                                'attribute' => 'adm_firstname',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_lastname',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_username',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_status',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'value' => function ($model) {
                                                                    if ($model->adm_status == '1') {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                    } else {
                                                                        return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                    }
                                                                },
                                                                'enableSorting' => True,
                                                            ],

                                                            [
                                                                'attribute' => 'campaign',
                                                                'label' => ManageAgentModule::t('manageagent', 'campaign'),
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'recent_login',
                                                                'label' => ManageAgentModule::t('manageagent', 'recent_login'),
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return CommonHelper::tsToDt($model->recent_login);
                                                                }
                                                            ],

                                                            /*[
                                                                'attribute' => 'campaign',
                                                                'label' => ManageAgentModule::t('manageagent', 'campaign'),
                                                                'headerOptions' => array('class' => 'text-center'),
                                                                'contentOptions' => array('class' => 'text-center'),
                                                                'enableSorting' => True,
                                                                'value' => function ($model, $key) {
                                                                    if (!empty($model->campaignMapUser)) {
                                                                        $string = implode(', ', \yii\helpers\ArrayHelper::map($model->campaignMapUser, 'campaign_id', 'campaign_id'));
                                                                        $array = explode(",", $string);
                                                                        $final_string = array();
                                                                        foreach ($array as $key => $val) {
                                                                            $camp_name = Campaign::find()->select(['cmp_name'])->where(['cmp_id' => $val])->one();
                                                                            $final_string[] = $camp_name->cmp_name;
                                                                        }
                                                                        $final_output = implode(", ", $final_string);
                                                                        return $final_output;
                                                                    }

                                                                    if (!empty($model->campaignMapAgent)) {

                                                                        $stringAgents = implode(', ', \yii\helpers\ArrayHelper::getColumn($model->campaignMapAgent, 'campaign_id', 'campaign_id'));

                                                                        $array = explode(",", $stringAgents);
                                                                        $final_agents_string = array();
                                                                        foreach ($array as $key => $val) {
                                                                            $camp_agents_name = Campaign::find()->select(['cmp_name'])->where(['cmp_id' => $val])->one();
                                                                            $final_agents_string[] = $camp_agents_name->cmp_name;
                                                                        }
                                                                        $final_agent_output = implode(", ", $final_agents_string);
                                                                        return $final_agent_output;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            ],*/
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
