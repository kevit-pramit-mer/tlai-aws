<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\views\template\generators\crud\BackendGenerator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form"
                     id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

                    <?= "<?php " ?>$form = ActiveForm::begin([
                    'class' => 'row',
                    'fieldConfig' => [
                    'options' => [
                    'class' => 'input-field col s12'
                    ],
                    ],
                    ]); ?>

                    <?php
                    foreach ($generator->getColumnNames() as $attribute) {
                        if (in_array($attribute, $safeAttributes)) {
                            ?>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                        <?php echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n"; ?>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?>
                            : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8']) ?>
                            <?= "<?php "?>if (!$model->isNewRecord) {?>
                                <?= "<?= " ?>Html::submitButton(Yii::t('app', 'apply'), [
                                'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                'name' => 'apply',
                                'value' => 'update']) ?>
                            <?= "<?php "?>}?>
                            <?= "<?= " ?>Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?= "<?php " ?>ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
