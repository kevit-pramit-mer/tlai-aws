<?php


use app\modules\ecosmob\admin\AdminModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $featuremodel app\modules\ecosmob\admin\models\FeatureMaster */
/* @var $dataProvider app\modules\ecosmob\admin\models\FeatureMaster */
?>
<?php $form = ActiveForm::begin([
    'id' => 'sunet-setting-form-id',
    'action' => Yii::$app->urlManager->createUrl(
        '/admin/feature'
    ),
]);
?>
<div class="card-accordions">
    <div id="accordion-service" role="tablist" aria-multiselectable="true">
        <div class="card">
            <div class="card-header card-custom">
                <a class="card-title" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    <h6 class="mb-0"><span class="fa fa-external-link"></span>
                        <?= AdminModule::t('admin', 'feature_settings') ?>
                    </h6>
                </a>
            </div>
            <div id="collapseThree" class="collapse">
                <div class="card-block">
                    <label class="btn-primary pull-right mr-1 help-btn-p ">
                        <i class="icon fa fa-question-circle fa-lg" data-toggle="popover"
                           data-trigger="hover" data-placement="left"
                           data-content="<?= AdminModule::t('admin', "startcode_notes") ?>"></i>
                    </label>
                    <?php
                    echo GridView::widget([
                        'id' => 'grid-aniforwarding-index',
                        'dataProvider' => $dataProvider,
                        'layout' => '{items}',
                        'options' => [
                            'class' => 'grid-view-color',
                        ],
                        'columns' => [
                            [
                                'label' => AdminModule::t('admin', 'description'),
                                'attribute' => 'ntc_feature_desc',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-center'],
                                'enableSorting' => True,
                            ],

                            [
                                'label' => AdminModule::t('admin', 'code'),
                                'attribute' => 'ntc_feature_code',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-center'],
                                'enableSorting' => True,
                                'value' => function ($featuremodel) {
                                    return getCode($featuremodel->ntc_feature_code, $featuremodel->ntc_feature_num,
                                        $featuremodel->ntc_feature_type);
                                }
                            ],

                        ],
                        'tableOptions' => [
                            'class' => 'table table-striped display nowrap table-bordered',
                        ],
                    ]);
                    ?>
                    <?php
                    function getCode($code, $id, $type)
                    {
                        if ($code == '0' && $type != 'SYSTEM') {
                            return Html::textInput($id, $code, [
                                'name' => $id,
                                "class" => "form-control",
                                "maxlength" => "3",
                                "onkeypress" => "return isNumberKeyFeature(event)",
                                "readonly" => true,
                            ]);
                        } else {
                            if ($type == 'SYSTEM') {
                                return '<div class="input-group" ><span class="input-group-addon" style = "width: 2px;" >*</span >' . Html::textInput($id,
                                        $code, array(
                                            'name' => $id,
                                            "class" => "form-control",
                                            'style' => ' display:inline-block',
                                            "maxlength" => "1",
                                            'onkeypress' => "return isNumberKeyForFeature(event)",
                                        )) . '</div>';
                            } else {
                                return Html::textInput($id, $code, array(
                                    'name' => $id,
                                    "class" => "form-control",
                                    "maxlength" => "3",
                                    'onkeypress' => "return isNumberKeyForFeature(event)",
                                ));
                            }
                        }
                    }

                    ?>


                </div>
                <div class="hseparator">
                </div>
                <div class="row">
                    <div class="form-group  col-xs-12 col-md-offset-10 ">
                        <?= Html::submitButton(AdminModule::t('admin', 'update'), ['class' => 'btn btn-primary btn-round-right']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
