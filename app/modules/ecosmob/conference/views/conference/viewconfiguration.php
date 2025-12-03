<?php

use app\modules\ecosmob\conference\ConferenceModule;

/* @var $multiModel */

$this->title = ConferenceModule::t('conference', 'configure_conference') . ' : ' . $multiModel[1]->cc_conf_group;
$this->params['breadcrumbs'][] = ['label' => ConferenceModule::t('conference', 'conference'), 'url' => ['index']];
$this->params['pageHead'] = $this->title;
?>
<div class="conference-master-configure"
     id="conference-master-configure">

    <?= $this->render('form/_viewconfiguration', [
        'multiModel' => $multiModel,
    ]) ?>
</div>
