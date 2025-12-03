<?php

use app\modules\ecosmob\conference\ConferenceModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceControls */
/* @var $multiModel */
/* @var $dataProvider */
/* @var $cm_name */

$this->title = ConferenceModule::t('conference', 'configure_conference') . ' : ' . $cm_name;
$this->params['breadcrumbs'][] = ['label' => ConferenceModule::t('conference', 'conference'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ConferenceModule::t('conference', 'configuration');
$this->params['pageHead'] = $this->title;
?>
<div class="conference-master-configure"
     id="conference-master-configure">

    <?= $this->render('form/_configuration', [
        'multiModel' => $multiModel,
    ]) ?>
</div>
