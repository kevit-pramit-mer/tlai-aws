<?php

use yii\db\Expression;
use yii\db\Query;

$expression = new Expression('YEAR(NOW())');
$year = (new Query)->select($expression)->scalar();
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>
<script>
    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var userType = "<?= $userType ?>";
</script>

<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow <?= isset($is_colapsed) && $is_colapsed ? 'footer-full' : '' ?>">
    <div class="footer-copyright">
        <div class="container">
            <div class="float-left">
                <p class="m-0">Powered By
                    <a href="https://www.ecosmob.com/" target="_blank">
                        <img src=" <?= Yii::getAlias('@web') . "theme\assets\images\yacosss.png" ?>"
                             class="footer-img" alt="Logo" />
                        <a>
                </p>
            </div>
            <span>&copy; <?= $year ?> <a href="#"
                                         target="_blank"><b><?= Yii::t('app', 'projectName') ?></b></a><?= Yii::t('app', 'all_rights_reserved') ?> </span>
        </div>
    </div>
</footer>

<script>
    $(document).on('click', '.sidenav-main .agent_navi', function () {
        //$(".sidenav-main li a").removeClass("active");
        //$(this).addClass("active");
        $(".agent_navi").removeClass("active");
        $(this).addClass("active");
    });

    if($(".sidenav-main").hasClass("nav-lock")) {
        //nothing happned
    } else {
        $(".sidenav-main ").hover(function(){
            $(".dialer-section").addClass("left-panel-open");
            }, function(){
                $(".dialer-section").removeClass("left-panel-open");
        });
    }

$(document).on('click','.navbar-toggler',function() {

        /* $('body .sidenav-main li').each(function(index, value) {
            if($(this).find("a").hasClass("active")) {
                $(this).find("a").trigger( "click" );
                console.log($(this).find("a").attr("href"));
                $(this).find("a").click();
                //$(this).attr('src') = $(this).find("a").attr("href");
            }
        }); */

        //$("body .sidenav-main li a.active").trigger( "click" );

        if ($("body .custom-sidenav-trigger").html() == "radio_button_checked") {
            //$("body #main").addClass("main-full");
            $("#agent_iframe").contents().find("#main").removeClass("main-full");
            $('footer').removeClass('footer-full');
            $(".dialer-section").addClass("left-panel-open");
            console.log($("#agent_iframe").contents().find(".dialer-section"));
        } else {
            //$("body #main").removeClass("main-full");
            $("#agent_iframe").contents().find("#main").addClass("main-full");
            $('footer').addClass('footer-full');
            $(".dialer-section").removeClass("left-panel-open");
            console.log($("#agent_iframe").contents().find(".dialer-section"));
        }

    });

    if($(".sidenav-main").hasClass("nav-lock")) {
        //nothing happned
    } else {
        $(".sidenav-main ").hover(function(){
            $(".dialer-section").addClass("left-panel-open");
            }, function(){
                $(".dialer-section").removeClass("left-panel-open");
        });
    }

    $(document).ready(function () {
        if (localStorage.getItem("toggle") == 1) { // open
            $('body .sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
            $('body .custom-sidenav-trigger').text('radio_button_checked');
            $("#agent_iframe").contents().find("#main").removeClass("main-full");
            $('footer').removeClass('footer-full');
            $(".dialer-section").addClass("left-panel-open");
        } else { // close
            $('body .sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
            $('body .custom-sidenav-trigger').text('radio_button_unchecked');
            $("#agent_iframe").contents().find("#main").addClass("main-full");
            $('footer').addClass('footer-full');
            $(".dialer-section").removeClass("left-panel-open");
        }

        if($(".sidenav-main").hasClass("nav-lock")) {
            //nothing happned
        } else {
            $(".sidenav-main ").hover(function(){
                $(".dialer-section").addClass("left-panel-open");
                }, function(){
                    $(".dialer-section").removeClass("left-panel-open");
            });
        }
    });


    /* HARDIK SARODIYA */
    function checkLogin() {
        $.ajax({
            type: 'POST',
            data: {},
            //async: false,
            url: baseURL + "index.php?r=auth/auth/isuserlogin",
            success: function (response) {
                if (response == "true" || response == '1') {
                } else {
                    window.localStorage.removeItem("call_mute");
                    window.localStorage.removeItem("call_hold");
                    window.localStorage.removeItem("crmupdatedlead");
                    window.localStorage.removeItem("crm");
                    window.location.href = baseURL + "index.php?r=auth/auth/login";
                }
            }
        });
    }

    $(document).ready(function () {
        window.setInterval(function () {
           checkLogin()
        }, 3000);
    });
    /* HARDIK SARODIYA */

</script>

