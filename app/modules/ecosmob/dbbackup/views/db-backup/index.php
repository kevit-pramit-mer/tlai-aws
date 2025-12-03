<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CommonHelper;
use app\modules\ecosmob\dbbackup\DbBackupModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\dbbackup\models\DbBackupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = DbBackupModule::t('app', 'db_backups');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <?php if (in_array('/dbbackup/db-backup/create', $permissions)) { ?>
        <?= Html::a(DbBackupModule::t('app', 'add_new'),
            ['create'],
            [
                'id' => 'hov',
                'data-pjax' => 0,
                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
            ]) ?>
    <?php } ?>
</div>

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

                                            <?= GridView::widget([
                                                'id' => 'db-backup-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#db-backup-search-form'),
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
                                                        'class' => 'yii\grid\ActionColumn',
                                                        'template' => '{download} {update}',
                                                        'header' => Yii::t('app', 'action'),
                                                        'headerOptions' => ['class' => 'center width-10'],
                                                        'contentOptions' => ['class' => 'center width-10'],
                                                        'buttons' => [
							    'download' => function ($url, $model) 
							    {
								if(file_exists($model->db_path))
							        {
									return (1 ? Html::a('<i class="material-icons">file_download</i>', $url, [
			                                                    'style' => '',
									    //'data-confirm' => DbBackupModule::t('app', 'backup_restore_confirm'),
			                                                    //'title' => $model->db_restore == 1 ? DbBackupModule::t('app', 'backup_proccess_start_soon_msg') : DbBackupModule::t('app', 'backup_restore'),
									    'title' => DbBackupModule::t('app', 'download'),
			                                                ]) : '');
								}
								
                                                            },	
                                                            'update' => function ($url, $model) 
							    {
								if($model->db_restore == 4)
								{
									return (1 ? Html::a('<i class="material-icons">restore</i>', "javascript::void(0);", [
		                                                            'style' => 'color: gray;',
		                                                            'disabled' => 'disabled',
		                                                            'title' => DbBackupModule::t('app', 'backup_proccess_error'),
		                                                        ]) : '');	

								}
								else if($model->db_restore == 3)
								{
									return (1 ? Html::a('<i class="material-icons">restore</i>', "javascript::void(0);", [
		                                                            'style' => 'color: gray;',
		                                                            'disabled' => 'disabled',
		                                                            'title' => DbBackupModule::t('app', 'backup_proccess_completed'),
		                                                        ]) : '');	

								}
								else if($model->db_restore == 2)
								{
									return (1 ? Html::a('<i class="material-icons">restore</i>', "javascript::void(0);", [
		                                                            'style' => 'color: gray;',
		                                                            'disabled' => 'disabled',
		                                                            'title' => DbBackupModule::t('app', 'backup_restore'),
		                                                        ]) : '');	

								}
							        else if($model->db_restore == 1)
								{
									return (1 ? Html::a('<i class="material-icons">restore</i>', "javascript::void(0);", [
		                                                            'style' => 'color: gray;',
		                                                            'disabled' => 'disabled',
		                                                            'title' => DbBackupModule::t('app', 'backup_proccess_start_soon_msg'),
		                                                        ]) : '');	

								} 
								else
								{
									return (1 ? Html::a('<i class="material-icons">restore</i>', $url, [
		                                                            'style' => '',
									    'data-confirm' => DbBackupModule::t('app', 'backup_restore_confirm'),
		                                                            'title' => $model->db_restore == 1 ? DbBackupModule::t('app', 'backup_proccess_start_soon_msg') : DbBackupModule::t('app', 'backup_restore'),
		                                                        ]) : '');
								}
                                                            }
                                                        ]
                                                    ],
                                                    [
                                                        'attribute' => 'db_name',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    /* [
                                                        'attribute' => 'db_date',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value' => function ($model) {
                                                            return CommonHelper::tsToDt($model->db_date, 'Y-m-d');
                                                        }

                                                    ], */
                                                    [
                                                        'attribute' => 'db_created_date',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value' => function ($model) {
                                                            return CommonHelper::tsToDt($model->db_created_date);
                                                        }
                                                    ],
						    /* [
                                                        'attribute' => 'db_restore_date',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value' => function ($model) 
							{
                                                            return CommonHelper::tsToDt($model->db_restore_date);
                                                        }
                                                    ],	*/
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline',
                                                    
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

