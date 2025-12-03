<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii2mod\rbac\models\AuthItemModel;

/* @var $this yii\web\View */
/* @var $model AuthItemModel */
/* @var $page_model AuthItemModel */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
$this->params['pageHead'] = $this->title;
?>


<div class="col 6 float-right">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?= Html::button('<i class="fa fa-check m-right-xs"></i>&nbsp;' . Yii::t('app', 'select_all_btn'),
        [
            'class' => 'mt-6 waves-effect waves-light gradient-45deg-red-pink inline-add-btn btn btn-warning float-xs-right btn-round-right btn-sm btn-margin',
            'id' => 'assign_all'
        ]) ?>

    <?= Html::a('<i class="fa fa-reply m-right-xs"></i>&nbsp;' . Yii::t('app', 'back'),
        ['index', 'page' => Yii::$app->session->get('page')],
        ['class' => 'mt-6 waves-effect waves-light gradient-45deg-red-pink inline-add-btn btn btn-primary float-xs-right btn-sm btn-margin']) ?>

    <?= Html::button('<i class="fa fa-plus m-right-xs"></i>&nbsp;' . Yii::t('app', 'assign'), [
        'class' => 'mt-6 waves-effect waves-light gradient-45deg-red-pink inline-add-btn btn btn-success float-xs-right btn-round-left btn-sm btn-margin',
        'onclick' => 'return AssignPageAccess()'
    ]) ?>
</div>


<?php Pjax::begin(['id' => 'assign_grid', 'enablePushState' => false, 'timeout' => 1000000]); ?>
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
                                        <?= GridView::widget([
                                            'id' => 'roles_grid',
                                            'dataProvider' => $page_model,
                                            'showOnEmpty' => true,
                                            'layout' => '{items}',
                                            'options' => [
                                                'class' => 'grid-view-color',
                                            ],
                                            'tableOptions' => [
                                                'class' => 'table table-striped display nowrap table-bordered sorting_asc',
                                                'id' => 'table1',
                                                'data-icons-prefix' => 'fa',
                                                'data-mobile-responsive' => 'false',
                                            ],
                                            'rowOptions' => ['class' => 'a_row'],
                                            'columns' => [
                                                [
                                                    'attribute' => 'page_desc',
                                                    'label' => Yii::t('app', 'page_desc'),
                                                    'enableSorting' => True,
                                                ],
                                                [
                                                    'class' => 'yii\grid\CheckboxColumn',
                                                    'name' => 'Create',
                                                    'checkboxOptions' => function ($data) {
                                                        $select = $data->createAssignmentCount();
                                                        $checked = ($data->page_create == 'Y' && $select) ? true : false;
                                                        return [
                                                            'id' => $data->pa_id . '_create',
                                                            'data_module' => $data->page_name,
                                                            'data_name' => $data->page_desc,
                                                            'data_priority' => $data->priority,
                                                            'style' => ($data->page_create == 'Y') ? 'display:block' : 'display:none',
                                                            'checked' => $checked,
                                                            'disabled' => ($data->page_create != 'Y'),
                                                            'class' => $data->pa_id . ' action_check filled-in',
                                                            'value' => 1
                                                        ];
                                                    },
                                                    'header' => Html::checkBox('create_all', false, [
                                                            'id' => 'create_all',
                                                        ]) . '&nbsp;' . Yii::t('app', 'create'),
                                                    'headerOptions' => ['class' => 'text-center all-checkbox-header'],
                                                    'contentOptions' => ['class' => "create_check text-center all-checkbox action_checkbox"],
                                                ],
                                                [
                                                    'class' => 'yii\grid\CheckboxColumn',
                                                    'name' => 'Modify',
                                                    'checkboxOptions' => function ($data) {
                                                        $select = $data->updateAssignmentCount();
                                                        $checked = ($data->page_update == 'Y' && $select) ? true : false;
                                                        return [
                                                            'id' => $data->pa_id . '_update',
                                                            'data_module' => $data->page_name,
                                                            'data_name' => $data->page_desc,
                                                            'data_priority' => $data->priority,
                                                            'style' => ($data->page_update == 'Y') ? 'display:block' : 'display:none',
                                                            'checked' => $checked,
                                                            'disabled' => ($data->page_update != 'Y'),
                                                            'class' => $data->pa_id . ' action_check',
                                                        ];
                                                    },
                                                    'header' => Html::checkBox('update_all', false, [
                                                            'id' => 'update_all',
                                                        ]) . '&nbsp;' . Yii::t('app', 'modify'),
                                                    'headerOptions' => ['class' => 'text-center all-checkbox-header'],
                                                    'contentOptions' => ['class' => "update_check text-center all-checkbox action_checkbox"],
                                                ],
                                                [
                                                    'class' => 'yii\grid\CheckboxColumn',
                                                    'name' => 'Delete',
                                                    'checkboxOptions' => function ($data) {
                                                        $select = $data->deleteAssignmentCount();
                                                        $checked = ($data->page_delete == 'Y' && $select) ? true : false;
                                                        return [
                                                            'id' => $data->pa_id . '_delete',
                                                            'data_module' => $data->page_name,
                                                            'data_name' => $data->page_desc,
                                                            'data_priority' => $data->priority,
                                                            'style' => ($data->page_delete == 'Y') ? 'display:block' : 'display:none',
                                                            'checked' => $checked,
                                                            'disabled' => ($data->page_delete != 'Y'),
                                                            'class' => $data->pa_id . ' action_check',
                                                        ];
                                                    },
                                                    'header' => Html::checkBox('delete_all', false, [
                                                            'id' => 'delete_all',
                                                        ]) . '&nbsp;' . Yii::t('app', 'delete'),
                                                    'headerOptions' => ['class' => 'text-center all-checkbox-header'],
                                                    'contentOptions' => ['class' => 'delete_check text-center all-checkbox action_checkbox'],
                                                ],
                                                [
                                                    'class' => 'yii\grid\CheckboxColumn',
                                                    'name' => 'View',
                                                    'checkboxOptions' => function ($data) {
                                                        $select = $data->viewAssignmentCount();
                                                        $checked = ($select > 0) ? true : false;
                                                        return [
                                                            'id' => $data->pa_id . '_index',
                                                            'data_module' => $data->page_name,
                                                            'data_name' => $data->page_desc,
                                                            'data_priority' => $data->priority,
                                                            'checked' => $checked,
                                                            'class' => $data->pa_id . ' view_check',
                                                            'value' => 1,
                                                        ];
                                                    },
                                                    'header' => Html::checkBox('view_all', false, [
                                                            'id' => 'view_all',
                                                            'class' => 'view_checkbox',
                                                        ]) . '&nbsp;' . Yii::t('app', 'view'),
                                                    'headerOptions' => ['class' => 'text-center all-checkbox-header'],
                                                    'contentOptions' => ['class' => 'view_checkbox text-center all-checkbox'],
                                                ],
                                            ],
                                        ])
                                        ?>
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
    window.rpath = '<?= Yii::$app->request->url; ?>';
    var custom_select_all = "<?php echo Yii::t('app', 'select_all_btn'); ?>";
    var custom_unselect_all = "<?php echo Yii::t('app', 'unselect_all_btn'); ?>";
</script>
<?php
$this->registerJsFile('@web/theme/assets/js/role_check.js');
?>
