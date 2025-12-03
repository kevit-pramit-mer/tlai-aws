<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\helpers\Url;

LoginAsset::register($this);
$logo = Yii::getAlias('@web') . "/theme/assets/images/yaco.png";
$faviconIcon = Yii::getAlias('@web') . "/theme/assets/images/favicon.png";
$getLogo = Yii::$app->session->get('getLogo');
if(!empty($getLogo)){
    if(!empty($getLogo['logo']) && !empty($getLogo['favicon_icon'])) {
        $logo = $getLogo['logo'];
        $faviconIcon = $getLogo['favicon_icon'];
    }
}
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
        <link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/fonts.css' ?>">
        <link rel="icon" href="<?= $faviconIcon ?>" type="image/x-icon" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 1-column login-bg  blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
        <?php $this->beginBody() ?>

        <div class="row">
            <div class="col s12 mt-2 set-m-center">
                        <img src="<?= $logo ?>" class="logo-image" height="50">
            </div>
        </div>
        <div class="row d-flex align-items-center auth-section-box">
            <div class="col s12 m6 text-left">
                <img src="<?= Yii::getAlias('@web') . "/theme/assets/images/login-img.svg" ?>" width="80%" class="login-image" />
                <!-- <h1>Sign in to<br/>your account</h1> -->

            </div>
            <div class="col s12 m6">
                <div class="container">
                    <div id="login-page" class="row auth-section">

                        <div class="z-depth-4 login-card">

                            <?php if (Yii::$app->session->hasFlash('success')) : ?>

                            <div class="alert card-alert card gradient-45deg-green-teal">
                                <div class="card-content white-text">
                                    <p>
                                        <i class="material-icons">check</i><?= Yii::$app->session->getFlash('success') ?></p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <?php endif; ?>

                            <?php if (Yii::$app->session->hasFlash('error')) : ?>

                            <div class="alert card-alert card gradient-45deg-red-pink">
                                <div class="card-content white-text">
                                    <p>
                                        <i class="material-icons">error</i><?= Yii::$app->session->getFlash('error') ?></p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <?php endif; ?>

                            <?= $content ?>

                        </div>
                </div>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>

<?php $this->endPage() ?>

<script>
    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
</script>
