<?php

use app\modules\ecosmob\speeddial\SpeeddialModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\speeddial\models\ExtensionSpeeddial */

$this->title = SpeeddialModule::t('app', 'create_ext_speeddial');
$this->params['breadcrumbs'][] = ['label' => SpeeddialModule::t('app', 'ext_speeddial'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-speeddial-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                        //'all_attribute' => $all_attribute
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>