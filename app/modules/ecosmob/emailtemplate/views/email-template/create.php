<?php

use yii\helpers\Html;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\emailtemplate\models\EmailTemplate */

$this->title                   = EmailTemplateModule::t( 'emailtemplate',"create_email_tmp" );
$this->params['breadcrumbs'][] = [ 'label' => EmailTemplateModule::t( 'emailtemplate',"email_tmp" ), 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = EmailTemplateModule::t( 'emailtemplate',"create" );
$this->params['pageHead']      = $this->title;
?>
<div class="email-template-create">
    
    <?= $this->render( '_form',
        [
            'model' => $model,
        ] ) ?>

</div>
