<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii2mod\rbac\RbacAsset;

RbacAsset::register($this);

/* @var $this yii\web\View */
/* @var $model \yii2mod\rbac\models\AssignmentModel */
/* @var $usernameField string */

$userName = $model->user->{$usernameField};
$this->title = Yii::t('yii2mod.rbac', 'Assignment : {0}', $userName);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;
$this->params['pageHead'] = $this->title;
?>
<div class="col s12">
     
</div>
</div>
</div>
</div>

<div class="row">
    <div class="col s12">
        <div class="card card-default">
            <div class="card-header">
                <form id="assignment-search-form"></form>
                <div class="basic_bootstrap_tbl custom-toolbar">

                    <div class="assignment-index">
                        <?php echo $this->render('../_dualListBox', [
                            'opts' => Json::htmlEncode([
                                'items' => $model->getItems(),
                            ]),
                            'assignUrl' => ['assign', 'id' => $model->userId],
                            'removeUrl' => ['remove', 'id' => $model->userId],
                        ]); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
