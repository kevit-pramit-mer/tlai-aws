<?php

use app\modules\ecosmob\conference\ConferenceModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceMaster */
/* @var $audioList */
/* @var $extensionList */

$this->title = ConferenceModule::t('conference', 'create_conference');
$this->params['breadcrumbs'][] = ['label' => ConferenceModule::t('conference', 'conference'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ConferenceModule::t('conference', 'create');
$this->params['pageHead'] = $this->title;
?>
<div class="conference-master-create"
     id="conference-master-create">

    <?= $this->render('form/_form', [
        'model' => $model
    ]) ?>

</div>
