<?php

use app\modules\ecosmob\conference\ConferenceModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceMaster */
/* @var $audioList */
/* @var $extensionList */

$this->title = ConferenceModule::t('conference', 'update_conference') . ": " . $model->cm_name;
$this->params['breadcrumbs'][] = ['label' => ConferenceModule::t('conference', 'conference'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ConferenceModule::t('conference', 'update');
$this->params['pageHead'] = $this->title;
?>
<div class="conference-master-update"
     id="conference-master-update">

    <?= $this->render('form/_form', [
        'model' => $model
    ]) ?>

</div>
