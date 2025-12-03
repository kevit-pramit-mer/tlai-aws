<?php
/* @var $this yii\web\View */

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkMaster */
/* @var $audioCodec app\models\CodecMaster */
/* @var $videoCodec app\models\CodecMaster */
/* @var $availableAudioCodecUpdate app\models\CodecMaster */
/* @var $availableVideoCodecUpdate app\models\CodecMaster */

$this->title = CarriertrunkModule::t('carriertrunk', 'update_trunk') . $model->trunk_name;
$this->params['breadcrumbs'][] = [
    'label' => CarriertrunkModule::t(
        'carriertrunk',
        'trunks'
    ),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = CarriertrunkModule::t(
    'carriertrunk',
    'update'
);
$this->params['pageHead'] = $this->title ?>

<div class="trunk-master-update">

    <?php try {
        echo $this->render(
            'form/_form',
            [
                'model' => $model,
                'audioCodec' => $audioCodec,
                'videoCodec' => $videoCodec,
                'availableAudioCodecUpdate' => $availableAudioCodecUpdate,
                'availableVideoCodecUpdate' => $availableVideoCodecUpdate,
            ]
        );
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>

</div>

