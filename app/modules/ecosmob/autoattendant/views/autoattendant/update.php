<?php

use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMaster */
/* @var $audioList app\modules\ecosmob\audiomanagement\models\AudioManagement */

$this->title = AutoAttendantModule::t('autoattendant', 'update_auto_attendant');
$this->params['breadcrumbs'][] = [
    'label' => AutoAttendantModule::t('autoattendant', 'auto_attendant'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = AutoAttendantModule::t('autoattendant', 'update');
$this->params['pageHead'] = $this->title;
?>
<div class="auto-attendant-master-update"
     id="auto-attendant-master-update">

    <?php try {
        /** @var array $outboundDialPlanList */
        echo $this->render('form/_form',
            [
                'model' => $model,
            ]);
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>

</div>
