<?php


/* @var $this yii\web\View */
use app\modules\ecosmob\extension\extensionModule;

/* @var $importModel app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $searchModel */
/* @var $dataProvider */

$this->title = extensionModule::t('app', 'import_extension');
$this->params['breadcrumbs'][] = ['label' => extensionModule::t('app', 'extensions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = extensionModule::t('app', 'import');
$this->params['pageHead'] = $this->title;

?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-import">
                    <?= $this->render( '_import',
                        [
                            'importModel'  => $importModel,
                            'searchModel'  => $searchModel,
                            'dataProvider' => $dataProvider,
                        ] ) ?>
                </div>
            </div>
        </div>
    </div>
</div>