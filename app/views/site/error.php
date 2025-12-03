<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="row">
    <div class="col s12">
        <div class="container"><div class="section section-404 p-0 m-0 height-100vh">
                <div class="row">
                    <!-- 404 -->
                    <div class="col s12 center-align white">
                        <img src="<?= Yii::getAlias('@web') . "/theme/assets/images/error-2.png" ?>" class="bg-image-404" alt="" width="100%">
                        <h1 class="error-code m-0"><?= Html::encode($this->title) ?></h1>
                        <h6 class="mb-2"><?= nl2br(Html::encode($message)) ?></h6>
                        <p>
                            The above error occurred while the Web server was processing your request.
                        </p>
                        <p>
                            Please contact us if you think this is a server error. Thank you.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
