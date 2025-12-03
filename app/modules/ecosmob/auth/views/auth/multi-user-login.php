<?php
/*/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\ecosmob\auth\models\LoginForm */
/* @var $users */
/* @var $username */

use app\assets\LoginAsset;
use app\modules\ecosmob\auth\AuthModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = AuthModule::t('auth', 'Login as');
$this->params['breadcrumbs'][] = $this->title;
LoginAsset::register($this);
?>
<div class="card-panel">
    <div class="row">
        <div class="col s12">
            <b><h6><?= $this->title ?></h6></b>
        </div>
    </div>

    <div class="row auth-section">
        <div id="campaign" class="col s12">
            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'class' => 'login-form',
                'action' => ['auth/saml-success-login', 'username' => $username],
            ]); ?>

            <div class="row margin user-selection-row">
                <?php $i = 0;
                foreach ($users as $k => $v) { ?>
                    <label>
                        <div class="usertype-selection">
                            <input type="radio" name="type" id="<?= $k ?>" value="<?= $k ?>" class="radio__input" checked="">
                            <label for="<?= $k ?>" class="radio__label">
                                <div class="set-for-radio"></div>
                                <?= $v ?>
                            </label>
                        </div>
                    </label>
                    <?php $i++;
                } ?>
            </div>

            <div class="row mt-8">
                <div class="col s12">
                    <input type="hidden" name="username" value="'.<?= $username ?>.'">
                    <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                        'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 login-user',
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="mt-3 col s12">
                    <p class="margin  medium-small">
                        <a href="<?= Url::to(['auth/login']) ?>" class="btn-link text-center waves-effect waves-light col s12"><?= AuthModule::t('auth', 'go_back') ?></a>
                    </p>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
/*    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var username = '<?= $username ?>';
    $(document).on('click', '.login-user', function() {
        $.ajax({
            type: 'POST',
            url: baseURL + "index.php?r=auth/auth/saml-success-login&username=" + username + "&type=" + $('.user-dropdown').val(),
            async: false,
            success: function (result) {
            }
        });
    });*/
</script>