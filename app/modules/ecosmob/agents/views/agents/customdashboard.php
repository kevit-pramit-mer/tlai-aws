<?php

use app\modules\ecosmob\agents\AgentsModule;
use app\modules\ecosmob\breaks\models\Breaks;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use app\components\CommonHelper;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\Campaign;
use yii\db\Expression;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalCalls */
/* @var $totalTalkTimeMinute */
/* @var $query */
/* @var $breakReason */
/* @var $model */

$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>

<script>
    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var userType = "<?= $userType ?>";
</script>
<?php

$data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id, 'break_status' => 'In'])->count();
$is_in = 0;
if ($data) {
    $is_in = 1;
}
$session = yii::$app->session;
$agentCamp = $session->get('selectedCampaign');

$loginAgent = Yii::$app->user->identity->adm_id;

$dataList = array();
$campaignList = CampaignMappingAgents::find()->select('campaign_id')->where(['agent_id' => $loginAgent])->asArray()->all();

$ids = implode(",", array_map(function ($a) {
    return implode("~", $a);
}, $campaignList));

$campaignListData = Campaign::find()->select(['cmp_id', 'cmp_name', 'cmp_type', 'cmp_dialer_type'])
    ->where(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
    ->andWhere(['cmp_id' => explode(',', $agentCamp)])
    ->andWhere(['cmp_status' => 'Active'])
    ->asArray()->all();
$campaignListType = CommonHelper::customMap($campaignListData, 'cmp_id', 'cmp_name', "cmp_type", "cmp_type");
?>

<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
</script>

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/plugins/rangePlugin.min.js"></script>
-->

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/flatpickr.min.css' ?>">
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/flatpickr.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/shortcut-buttons-flatpickr.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/rangePlugin.min.js' ?>"></script>

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/materialize.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/style.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/newvendors.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/select2.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/data-tables.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/jquery.dataTables.min.css' ?>">
<link rel="stylesheet"
      href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/responsive.dataTables.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/select.dataTables.min.css' ?>">

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/jquery.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/plugins.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/vendors.min.js' ?>"></script>

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/select2.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-masks.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-layouts.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/jquery.formatter.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/data-tables.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.select.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/multiselect.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/custom.js' ?>"></script>


<script>
    $(document).ready(function () {
        if (localStorage.getItem("toggle") == 1) { // open
            $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
            $('.custom-sidenav-trigger').text('radio_button_checked');
            $('#main').removeClass('main-full');
            $('footer').removeClass('footer-full');
        } else { // close
            $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
            $('.custom-sidenav-trigger').text('radio_button_unchecked');
            $('#main').addClass('main-full');
            $('footer').addClass('footer-full');
        }
    });
</script>

<?php if (Yii::$app->session->hasFlash('success')) : ?>
    <div class="col s4 right alert set-alert-theme fixed-alert" role="alert">
        <div class="card-alert card gradient-45deg-green-teal mt-1">
            <div class="row">
                <div class="col s10">
                    <div class="card-content white-text">
                        <p>
                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('success') ?>
                        </p>
                    </div>
                </div>
                <div class="col s2">
                    <button type="button" class="close white-text" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <div class="col s4 right alert set-alert-theme fixed-alert" role="alert">
        <div class="card-alert card gradient-45deg-red-pink mt-1">
            <div class="row">
                <div class="col s10">
                    <div class="card-content white-text">
                        <p>
                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('error') ?>
                        </p>
                    </div>
                </div>
                <div class="col s2">
                    <button type="button" class="close white-text" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<script>
    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
</script>
<?php
$mainClass = '';
if (Yii::$app->session->get('loginAsExtension')) {
    $mainClass = 'extension-main';
} else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
    $mainClass = 'supervisor-main';
} else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
    $mainClass = 'agent-main';
} else {
    $mainClass = 'tenant-main';
}
?>
<div id="main" class="<?= $mainClass ?> main-full">
    <div class="row">
        <div class="col s12 p-0 pt-1">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark pb-0 pt-1 col s12 m5" id="breadcrumbs-wrapper">
                    <div class="col m12">
                        <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                        <?= Breadcrumbs::widget([
                            'tag' => 'ol',
                            'options' => ['class' => 'breadcrumbs mb-0'],
                            'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                            'homeLink' => [
                                'label' => Yii::t('yii', 'Home'),
                                'url' => Yii::$app->homeUrl,
                                'encode' => false// Requested feature
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>
                </div>
                <div id="">
                    <div class="row">
                        <div class="col s12">
                            <div class="container">
                                <div id="card-stats">
                                    <div class="row mb-2">
                                        <div class="col s12 m5 dashboard-selection">
                                            <?php $form = ActiveForm::begin([
                                                'class' => 'mb-0',
                                                'fieldConfig' => [
                                                    'options' => [
                                                        'class' => 'input-field'
                                                    ],
                                                ],
                                            ]);
                                            ?>
                                            <div class="row">
                                                <div class="col s12 pl-3">
                                                    <div id="campaign-cmp_type">
                                                        <div class="select-wrapper">
                                                            <?= $form->field($breakReason, 'camp_id', ['options' => ['class' => '']])->dropDownList($campaignListType, ['prompt' => AgentsModule::t('agents', 'prompt_camp1')])->label(false); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                        <div class="col s12 m7 right-align-sm break-in-out">
                                            <!-- <i class="material-icons mr-2">query_builder</i> -->
                                            <?php echo Html::Button(AgentsModule::t('agents', 'break_in'), ['class' => 'btn btn-basic In', 'style' => ($is_in) ? 'display: none' : 'display:inline-block']) ?>
                                            <?php echo Html::Button(AgentsModule::t('agents', 'break_out'), ['class' => 'btn btn-basic Out', 'style' => (!$is_in) ? 'display:none' : 'display:inline-block']) ?>
                                        </div>
                                   <!-- <div class="row">
                                        <?php /*$form = ActiveForm::begin([
                                            'class' => 'row',
                                            'fieldConfig' => [
                                                'options' => [
                                                    'class' => 'input-field col s12'
                                                ],
                                            ],
                                        ]); */?>
                                        <div class="row">
                                            <div class="col s6">
                                                <div class="input-field col s12" id="campaign-cmp_type">
                                                    <div class="select-wrapper">
                                                        <?php /*= $form->field($model, 'adm_firstname', ['options' => ['class' => '']])->dropDownList($campaignListType = CommonHelper::customMap($campaignListData, 'cmp_id', 'cmp_name', "cmp_type", "cmp_type"), ['prompt' => Yii::t('app', '-- Select Campaign --')])->label(Yii::t('app', 'Select Campaign')); */?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*ActiveForm::end(); */?>
                                    </div>-->
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?php echo AgentsModule::t('agents', 'ttl_call') ?></p>
                                                        <h4 class="card-stats-number m-0"
                                                            id="totalCalls"><?= $totalCalls; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?php echo AgentsModule::t('agents', 'ttl_talk_call') ?></p>
                                                        <h4 class="card-stats-number m-0"
                                                            id="totalTalkTimeMinute"><?= $totalTalkTimeMinute; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?php echo AgentsModule::t('agents', 'break_time') ?></p>
                                                        <h4 class="card-stats-number m-0" id="query"><?= $query; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->

                <div id="modal1" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo AgentsModule::t('agents', 'break_reason') ?></h5>
                            <span aria-hidden="true" class="close">&times;</span>
                        </div>
                        <?php $form = ActiveForm::begin([
                            'class' => 'row',
                            'id' => 'submit-break-form',
                            'action' => ['supervisor/supervisor/submit-break-reason'],
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field'
                                ],
                            ],
                        ]); ?>
                        <div class="modal-body">
                            <div class="campaign-form" id="break-form">
                                <div class="row row pl-2 pr-2 pt-2">
                                    <div class="col s12" id="week_off_queue">
                                        <div class="select-wrapper">
                                            <?php
                                            $breakList = Breaks::find()->select(['br_id', 'br_reason'])->all();
                                            $breakList = ArrayHelper::map($breakList, 'br_id', 'br_reason');
                                            echo $form->field($breakReason, 'break_reason', ['options' => ['class' => '']])->dropDownList($breakList, ['prompt' => AgentsModule::t('agents', 'prompt_break')])->label(AgentsModule::t('agents', 'select_break')); ?>
                                            <div id="breakError" class="help-block">
                                                <?php echo AgentsModule::t('agents', 'break_reason_required') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?= Html::submitButton(AgentsModule::t('agents', 'submit'), [
                                'class' => 'btn waves-effect waves-light amber darken-4',
                                'name' => 'apply',
                                'value' => 'update']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

                <style>
                    #main .content-wrapper-before {
                        top: 0 !important;
                    }

                    .card-content.cyan.white-text {
                        min-height: 145px !important;
                    }

                    label {
                        font-size: 1rem;
                        /*color: #4a4a4a;*/
                    }
                </style>

                <script type="text/javascript">
                    window.setInterval(function () {
                        updateDashboard();
                        breakBtnShowHide();
                    }, 1000);
                    $(document).ready(function () {
                        $('#breakError').hide();
                        // submitBreakReason();
                        //$('.modal').modal();
                        $('.modal').modal()[0].M_Modal.options.dismissible = false;
                        $('.break-in-out').removeClass('d-none');
                    });
                    $(document).on("click", ".In", function () {
                        $('.modal').modal('open');
                    });

                    $(document).on("click", ".Out", function () {
                        $('.Out').hide();
                        $('.In').show();
                        submitBreakReason('out');
                    });

                    $(document).on("submit", "#submit-break-form", function (e) {
                        e.preventDefault();
                        if ($('#breakreasonmapping-break_reason').val() == '') {
                            document.getElementById("submit-break-form").reset();
                            $('#breakError').show();
                            return false;
                        }
                        $('#breakError').hide();
                        submitBreakReason('in');
                        // updateDashboard();
                        document.getElementById("submit-break-form").reset();
                        $('.modal').modal('close');
                    });

                    function submitBreakReason(type) {
                        var data = $('#submit-break-form').serializeArray();
                        data.push({'name': 'type', 'value': type}, {'name': 'cmp_id', 'value': $('#breakreasonmapping-camp_id').val()});

                        $.ajax({
                            async: false,
                            data: data,
                            type: 'POST',
                            url: baseURL + "index.php?r=supervisor/supervisor/submit-break-reason",
                            success: function (result) {
                                //if (type == 'out') {
                                    //window.top.location.reload();
                                //}
                            }
                        });
                        if (type == 'in') {
                            $('.In').hide();
                            $('.Out').show();
                        }
                        if (type == 'out') {
                            $('.In').show();
                            $('.Out').hide();
                        }
                    }

                    function updateDashboard() {
                        var camp_id = $('#campaign-cmp_type').find(':selected').val();
                        $.ajax({
                            type: 'GET',
                            url: baseURL + "index.php?r=agents/agents/updatedashboard&camp_id="+camp_id,
                            //async: false,
                            success: function (result) {
                                let final_data = $.parseJSON(result);
                                $("#query").text(final_data.query);
                                $("#totalCalls").text(final_data.totalCalls);
                                $("#totalTalkTimeMinute").text(final_data.totalTalkTimeMinute);
                                //console.log(result)

                            }
                        });
                    }

                    $(document).on('change', '#breakreasonmapping-camp_id', function () {
                        var camp_id = $('#campaign-cmp_type').find(':selected').val();
                        $.ajax({
                            type: 'GET',
                            url: baseURL + "index.php?r=agents/agents/updatedashboard&camp_id="+camp_id,
                            async: false,
                            success: function (result) {
                                let final_data = $.parseJSON(result);
                                $("#query").text(final_data.query);
                                $("#totalCalls").text(final_data.totalCalls);
                                $("#totalTalkTimeMinute").text(final_data.totalTalkTimeMinute);
                                //console.log(result)

                            }
                        });
                    });


                    $(document).on("click", ".close", function () {
                        $('.modal').modal('close');
                    });
                </script>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        /* console.log("============================================================");
        console.log($("body .sidenav-main .navbar-toggler .material-icons").html());
        if($("body .sidenav-main .navbar-toggler .material-icons").html() == "radio_button_checked")
        {
            alert("Checked");
            $("#agent_iframe").contents().find("#main").removeClass("main-full");
        }
        else
        {
            alert("Unchecked");
            $("#agent_iframe").contents().find("#main").addClass("main-full");
        } */
        /* $(document).ready(function () {
            console.log(localStorage.getItem("toggle"));
            if (localStorage.getItem("toggle") == 1) { // open
                $('body .sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('body .sidenav-main').children().find('.navbar-toggler .material-icons').text('radio_button_checked');
                 $("#agent_iframe").contents().find("#main").removeClass("main-full");
            } else { // close
                $('body .sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('body .sidenav-main').children().find('.navbar-toggler .material-icons').text('radio_button_unchecked');
                $("#agent_iframe").contents().find("#main").addClass("main-full");
            }
            }); */
    });
    function breakBtnShowHide(){
        $.ajax({
            type: 'GET',
            url: baseURL + "index.php?r=agents/agents/in-call",
            //async: false,
            success: function (result) {
                if (result) {
                    $('.break-in-out').addClass('d-none');
                } else {
                    $('.break-in-out').removeClass('d-none');
                }
            }
        });
    }
</script>




