<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\views\template\generators\crud\NtcarfteGenerator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app','search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search"
                     id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

                    <?= "<?php " ?> $form = ActiveForm::begin([
                    'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search-form',
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => [
                    'data-pjax' => 1
                    ],
                    'fieldConfig' => [
                    'options' => [
                    'class' => 'input-field col s12'
                    ],
                    ],
                    ]); ?>

                    <?php
                    $count = 0;
                    $flag = 0;
                    foreach ($generator->getColumnNames() as $attribute) {
                        if (++$count < 6) { ?>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                        <?php echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n"; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="input-field center">
                            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' =>
                            'btn waves-effect waves-light amber darken-4']) ?>
                            <?= "<?= " ?>Html::a(<?= $generator->generateString('Reset') ?>, ['index', 'page' =>
                            Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>


                    <?= "<?php " ?>ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
