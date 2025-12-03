<?php

use yii\helpers\Html;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\emailtemplate\models\EmailTemplate */

$this->title                   = EmailTemplateModule::t( 'emailtemplate','update_email_tmp' ) . $model->key;
$this->params['breadcrumbs'][] = [ 'label' => EmailTemplateModule::t( 'emailtemplate','email_tmp' ), 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = EmailTemplateModule::t( 'emailtemplate','update' );
$this->params['pageHead']      = $this->title;
?>
<div class="email-template-update">
    
    <?= $this->render( '_form',
        [
            'model' => $model,
        ] ) ?>

</div>
