<?php

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroup */
/* @var $trunkMaster */
/* @var $trunkGroupDetails */

$this->title = CarriertrunkModule::t('carriertrunk', 'create_trunk_group');
$this->params['breadcrumbs'][] = [
    'label' => CarriertrunkModule::t(
        'carriertrunk',
        'trunk_groups'
    ),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = CarriertrunkModule::t(
    'carriertrunk',
    'create'
);
$this->params['pageHead'] = $this->title;

?>
<div class="ntc-trunk-group-create">

    <?php try {
        echo $this->render('form/_form',
            [
                'model' => $model,
                'trunkMaster' => $trunkMaster,
                'trunkGroupDetails' => $trunkGroupDetails,
            ]);
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>

</div>
