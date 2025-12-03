<?php

use yii\helpers\Html;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook */

$this->title = EnterprisePhonebookModule::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => EnterprisePhonebookModule::t('app', 'enterprise_phonebook'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="enterprise-phonebook-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>