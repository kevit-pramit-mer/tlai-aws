<?php

use app\modules\ecosmob\queue\QueueModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queue\models\QueueMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="queue-master-search"
                     id="queue-master-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'queue-master-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'qm_name')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('qm_name'))]) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'qm_extension')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('qm_extension'))]) ?>
                        </div>
                    
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(QueueModule::t('queue', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(QueueModule::t('queue', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get('page'),
                                    ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
            </div>
        </div>
    </li>
</ul>
