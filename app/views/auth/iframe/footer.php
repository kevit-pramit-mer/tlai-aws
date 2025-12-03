<?php

use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;

$expression = new Expression('YEAR(NOW())');
$year = (new Query)->select($expression)->scalar();

?>

<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <div class="float-left">
                <p class="m-0">Powered By
                    <a href="https://www.ecosmob.com/" target="_blank">
                        <img src=" <?= Yii::getAlias('@web') . "theme\assets\images\yacosss.png" ?>"
                             class="footer-img"/>
                        <a>
                </p>
            </div>
            <span>&copy; <?= $year ?> <a href="#"
                                         target="_blank"><b><?= Yii::t('app', 'projectName') ?></b></a><?= Yii::t('app', 'all_rights_reserved') ?> </span>
        </div>
    </div>
</footer>

<script>
    function checkLogin() {
        $.ajax({
            type: 'POST',
            data: {},
            //async: false,
            url: baseURL + "index.php?r=auth/auth/isuserlogin",
            success: function (response) {
                if (response == "true" || response == '1') {
                } else {
                    window.location.href = baseURL + "index.php?r=auth/auth/login";
                }
            }
        });
    }
    $(document).on('click', '.sidenav-main .extension_navi', function () {
        //$(".sidenav-main li a").removeClass("active");
        //$(this).addClass("active");
        $(".extension_navi").removeClass("active");
        $(this).addClass("active");
    });
    $(document).on('click', '.navbar-toggler', function () {

        if ($("body .custom-sidenav-trigger").html() == "radio_button_checked") {
            $("#extension_iframe").contents().find("#main").removeClass("main-full");
            $('footer').removeClass('footer-full');
            $(".dialer-section").addClass("left-panel-open");
        } else {
            $("#agent_iframe").contents().find("#main").addClass("main-full");
            $("#extension_iframe").contents().find("#main").addClass("main-full");
            $('footer').addClass('footer-full');
            $(".dialer-section").removeClass("left-panel-open");
        }

    });
    $(document).ready(function () {
        $('.navbar-toggler').trigger('click');
        if (localStorage.getItem("toggle") == 1) { // open
            $('body .sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
            $('body .custom-sidenav-trigger').text('radio_button_checked');
            $("#extension_iframe").contents().find("#main").removeClass("main-full");
            $('footer').removeClass('footer-full');
            $(".dialer-section").addClass("left-panel-open");
        } else { // close
            $('body .sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
            $('body .custom-sidenav-trigger').text('radio_button_unchecked');
            $("#extension_iframe").contents().find("#main").addClass("main-full");
            $('footer').addClass('footer-full');
            $(".dialer-section").removeClass("left-panel-open");
        }
        window.setInterval(function () {
            checkLogin()
        }, 3000);
    });
</script>
