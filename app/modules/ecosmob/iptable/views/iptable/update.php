<?php

use app\modules\ecosmob\iptable\IpTableModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\iptable\models\IpTable */

$this->title = IpTableModule::t('it', 'update_ip') . $model->it_source;
$this->params['breadcrumbs'][] = ['label' => IpTableModule::t('it', 'ip_tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = IpTableModule::t('it', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="ip-table-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>