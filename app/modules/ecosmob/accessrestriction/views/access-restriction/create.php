<?php

use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\accessrestriction\models\AccessRestriction */

$this->title = AccessRestrictionModule::t('accessrestriction', 'create');
$this->params['breadcrumbs'][] = ['label' => AccessRestrictionModule::t('accessrestriction', 'access_restriction'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="access-restriction-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>