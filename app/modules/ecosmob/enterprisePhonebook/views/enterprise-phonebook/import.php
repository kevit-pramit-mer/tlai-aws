<?php


use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;

/* @var $this yii\web\View */
/** @var $searchModel */
/** @var $dataProvider */
/* @var $importModel \app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook */

$this->title = EnterprisePhonebookModule::t('app', 'import_enterprise_phonebook');
$this->params['breadcrumbs'][] = ['label' => EnterprisePhonebookModule::t('app', 'enterprise_phonebook'), 'url' => ['index']];
$this->params['breadcrumbs'][] = EnterprisePhonebookModule::t('app', 'import');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="enterprise-phonebook-import">
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