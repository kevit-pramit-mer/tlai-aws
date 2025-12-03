<?php
/* @var $this yii\web\View */

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\base\InvalidCallException;
use yii\base\ViewNotFoundException;

/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkMaster */
/* @var $availableAudioCodecs app\models\CodecMaster */
/* @var $availableVideoCodecs app\models\CodecMaster */

$this->title = CarriertrunkModule::t('carriertrunk', 'create_trunk');
$this->params['breadcrumbs'][] = [
    'label' => CarriertrunkModule::t(
        'carriertrunk',
        'trunks'
    ),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = CarriertrunkModule::t(
    'carriertrunk',
    'create'
);
$this->params['pageHead'] = $this->title;
?>
<div class="trunk-master-create">
    <?php try {
        echo $this->render(
            'form/_form',
            [
                'model' => $model,
                'availableAudioCodecs' => $availableAudioCodecs,
                'availableVideoCodecs' => $availableVideoCodecs,
            ]
        );
    } catch (InvalidCallException $e) {
    } catch (ViewNotFoundException $e) {
    } ?>
</div>
