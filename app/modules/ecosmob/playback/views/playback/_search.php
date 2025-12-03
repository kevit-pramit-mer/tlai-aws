<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\playback\PlaybackModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\playback\models\PlaybackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?php echo Yii::t('app','search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="playback-search"
                     id="playback-search">

                    <?php $form = ActiveForm::begin( [
                        'id'          => 'playback-search-form',
                        'action'      => [ 'index' ],
                        'method'      => 'get',
                        'options'     => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ] ); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field( $model, 'pb_name' ) ?>
                        </div>
                        <div class="col s12 m6">
                                <?= $form->field( $model, 'pb_language', ['options' => ['class' => '']] )
                                    ->dropDownList(['English' => Yii::t('app','english'), 'Spanish' => Yii::t('app','spanish')], ['promptc' => PlaybackModule::t('pb', 'all')])->label(PlaybackModule::t('pb', 'language')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton( PlaybackModule::t('pb', 'search'),
                                [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4',
                                ] ) ?>
                            <?= Html::a( PlaybackModule::t('pb', 'reset'),
                                [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get( 'page' ),
                                ],
                                [ 'class' => 'btn waves-effect waves-light bg-gray-200 ml-1' ] ) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
