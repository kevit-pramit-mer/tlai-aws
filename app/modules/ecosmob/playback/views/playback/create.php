<?php

use app\modules\ecosmob\playback\PlaybackModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\playback\models\Playback */

$this->title = PlaybackModule::t('pb', 'create_playback');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','playback'), 'url' => ['index']];
$this->params['breadcrumbs'][] = PlaybackModule::t( 'pb', 'create' );
$this->params['pageHead'] = $this->title;
?>

</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="playback-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
