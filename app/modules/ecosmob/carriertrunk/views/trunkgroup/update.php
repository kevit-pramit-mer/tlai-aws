<?php

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroup */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroupDetails */
/* @var $left_trunks */
/* @var $right_trunks */

$this->title = CarriertrunkModule::t('carriertrunk', 'update_trunk_group') . $model->trunk_grp_name;
$this->params['breadcrumbs'][] = [
    'label' => CarriertrunkModule::t(
        'carriertrunk',
        'trunk_groups'
    ),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = CarriertrunkModule::t(
    'carriertrunk',
    'update'
);
$this->params['pageHead'] = $this->title;

?>
<div class="ntc-trunk-group-update">
    <?php try {
        echo $this->render(
            'form/_form',
            [
                'model' => $model,
                'left_trunks' => $left_trunks,
                'right_trunks' => $right_trunks,
            ]
        );
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>
</div>
