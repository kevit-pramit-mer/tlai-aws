<?php

use app\modules\ecosmob\pcap\PcapModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\pcap\models\PcapSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header">Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="pcap-search"
                     id="pcap-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'pcap-search-form',
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
                        <div class="col s6">
                            <?= $form->field($model, 'ct_name') ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(PcapModule::t('pcap', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(PcapModule::t('pcap', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get('page'),
                                    ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
