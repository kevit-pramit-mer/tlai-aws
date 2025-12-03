<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\views\template\generators\crud\BackendGenerator*/

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?= "<?= " ?>Html::a('Add New', ['create'], [
        'id' => 'hov',
        'data-pjax' => 0,
        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
    ]) ?>
</div>
</div>
</div>
</div>

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

                                        <?= "<?=" ?> $this->render('_search', ['model' => $searchModel]); ?>

                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?= $generator->enablePjax ? '<?php Pjax::begin([\'enablePushState\' => false, \'id\' => \'pjax-' . Inflector::camel2id(StringHelper::basename($generator->modelClass)) . '\']); ?>' : '' ?>

                                            <?php if ($generator->indexWidgetType === 'grid'): ?>
                                                <?= "<?= " ?>GridView::widget([
                                                'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search-form'),
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                ],
                                                'options' => [
                                                'tag' => false,
                                                ],
                                                <?= !empty($generator->searchModelClass) ? "'columns' => [\n" : "'columns' => [\n"; ?>
                                                [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{update}{delete}',
                                                'header' => Yii::t('app', 'action'),
                                                'headerOptions' => ['class' => 'center width-10'],
                                                'contentOptions' => ['class' => 'center width-10'],
                                                'buttons' => [
                                                'update' => function ($url, $model) {
                                                return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                                                       'style' => '',
                                                                                                       'title' => Yii::t('app', 'update'),
                                                                                                       ]) : '');
                                                                                                 },
                                                 'delete' => function ($url, $model) {
                                                 return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                'class' => 'ml-5',
                                                'data-pjax' => 0,
                                                'style' => 'color:#FF4B56',
                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                'data-method' => 'post',
                                                'title' => Yii::t('app', 'delete'),
                                                ]) : '');
                                                },
                                                ]
                                                ],

                                                <?php
                                                $count = 0;
                                                if (($tableSchema = $generator->getTableSchema()) === false) {
                                                    foreach ($generator->getColumnNames() as $name) {
                                                        if (++$count < 6) {
                                                            echo "            '" . $name . "',\n";
                                                        } else {
                                                            echo "            // '" . $name . "',\n";
                                                        }
                                                    }
                                                } else {

                                                    $tablePrimaryKey = isset($tableSchema->primaryKey[0]) ? $tableSchema->primaryKey[0] : '';

                                                    foreach ($tableSchema->columns as $column) {
                                                        $format = $generator->generateColumnFormat($column);

                                                        if (preg_match('/(status)$/i', $column->name)) {
                                                            echo "            [\n'attribute'=>'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n'format' => 'raw',\n'headerOptions'=>['class' => 'text-center'],\n'contentOptions' => ['class' => 'text-center'],\n'value'=>function(" . '$model){return $model->' . "$column->name == 'Y' ? Yii::t('app',
                                        '<span class=\"tag square-tag tag-success tag-custom\">' . Yii::t('app',
                                            'active') . '</span>') :
                                        Yii::t('app',
                                            '<label class=\"tag square-tag tag-danger tag-custom\">' . Yii::t('app',
                                                'inactive') . '</label>'); }\n],";
                                                        }
                                                        if (++$count < 6) {
                                                            echo "            [\n'attribute'=>'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n'headerOptions'=>['class' => 'text-center'],\n'contentOptions' => ['class' => 'text-center'],\n],\n";
                                                        } else {
                                                            echo "            /*[\n'attribute'=>'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n'headerOptions'=>['class' => 'text-center'],\n'contentOptions' => ['class' => 'text-center'],\n],\n*/";
                                                        }
                                                    }
                                                }
                                                ?>
                                                ],
                                                'tableOptions' => [
                                                'class' => 'display dataTable dtr-inline',
                                                
                                                ],
                                                ]); ?>
                                            <?php else: ?>
                                                <?= "<?= " ?>ListView::widget([
                                                'dataProvider' => $dataProvider,
                                                'itemOptions' => ['class' => 'item'],
                                                'itemView' => function ($model, $key, $index, $widget) {
                                                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                                                },
                                                ]) ?>
                                            <?php endif; ?>

                                            <?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>

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

