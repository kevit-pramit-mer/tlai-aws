<?php


use app\modules\ecosmob\phonebook\assets\PhoneBookAsset;
use app\modules\ecosmob\phonebook\PhoneBookModule;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var $searchModel */
/** @var $dataProvider */
/* @var $importModel app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */

$this->title = PhoneBookModule::t('app', 'import_phonebook');
$this->params['breadcrumbs'][] = ['label' => PhoneBookModule::t('app', 'phone_book'), 'url' => ['index']];
$this->params['breadcrumbs'][] = PhoneBookModule::t('app', 'import');
$this->params['pageHead'] = $this->title;
PhoneBookAsset::register($this);
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
                <div class="col-md-12 profile-contain">
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="content">
                                <div class="phone-book-import">
                                    <?= $this->render('_import',
                                        [
                                            'importModel' => $importModel,
                                            'searchModel' => $searchModel,
                                            'dataProvider' => $dataProvider,
                                        ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
