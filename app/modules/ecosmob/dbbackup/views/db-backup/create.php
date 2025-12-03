<?php

use app\modules\ecosmob\dbbackup\DbBackupModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */

$this->title = DbBackupModule::t('app', 'create_db_backup');
$this->params['breadcrumbs'][] = ['label' => DbBackupModule::t('app', 'db_backup'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t( 'app', 'create' );
$this->params['pageHead'] = $this->title;
?>

</div>
</div>
</div>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="prompt-list-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
