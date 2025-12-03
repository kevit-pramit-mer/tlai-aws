<?php

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding */

$this->title = Yii::t('app', 'update_ext_forw');
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension Forwardings'), 'url' => ['index']];*/
/*$this->params['breadcrumbs'][] = ['label' => $model->ef_id, 'url' => ['index']];*/
$this->params['breadcrumbs'][] = Yii::t('app', 'update_ext_forw');
$this->params['pageHead'] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/extension/extension/dashboard']),
                            //'url' => 'javascript:void(0);',
                            'target' => "extensionFrame",
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="row m-0">
                    <div class="col-md-12 profile-contain">
                        <div class="content">
                            <div class="extension-forwarding-update">

                                <?= $this->render('_form', [
                                    'model' => $model,
                                    'holidaylist' => $holidaylist,
                                    'internalList' => $internalList,
                                    'shiftList' => $shiftList,
                                    'weekOfList' => $weekOfList,
                                    'call_setting_model' => $call_setting_model,
                                    'findme_followme_model' => $findme_followme_model,
                                ]) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
