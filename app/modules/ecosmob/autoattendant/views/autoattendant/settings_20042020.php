<?php

use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMaster */
/* @var $audioList app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $autoAttendantKeys app\modules\ecosmob\autoattendant\models\AutoAttendantKeys */
/* @var $detailModelError app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $allAutoAttendantDetails app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $allData app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $autoDetail app\modules\ecosmob\autoattendant\models\AutoAttendantDetail */
/* @var $jsTreeData app\modules\ecosmob\autoattendant\controllers\AutoattendantController */
$this->title = AutoAttendantModule::t('autoattendant', 'auto_attendant_settings');
$this->params['breadcrumbs'][] = [
    'label' => AutoAttendantModule::t('autoattendant', 'auto_attendant'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = AutoAttendantModule::t('autoattendant', 'settings');
$this->params['pageHead'] = $this->title;
?>
<div class="auto-attendant-master-settings"
     id="auto-attendant-master-settings">

    <?php try {
        echo $this->render('form/_settings',
            [
                'model' => $model,
                'autoAttendantKeys' => $autoAttendantKeys,
                'detailModelError' => $detailModelError,
                'allData' => $allData,
                'autoDetail' => $autoDetail,
                'allAutoAttendantDetails' => $allAutoAttendantDetails,
                'jsTreeData' => $jsTreeData,
            ]);
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>

</div>
