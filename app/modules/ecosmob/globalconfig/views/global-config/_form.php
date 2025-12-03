<?php

use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\globalconfig\GlobalConfigModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\globalconfig\models\GlobalConfig */
/* @var $form yii\widgets\ActiveForm */
/* @var $selectedCodecs array */
?>
<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'globalConfForm',
            'options' => ['enctype' => 'multipart/form-data'],
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="create_time_condition_form" id="create_time_condition_form">      
                    <div class="row">
                        <div class="col s12 m6" hidden>
                            <div class="input-field">
                                <?= $form->field($model, 'gwc_key')->textInput([
                                    'disabled' => TRUE,
                                    'maxlength' => TRUE,
                                ])->label(GlobalConfigModule::t('gc', 'key')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'gwc_description')->textarea(
                                    [
                                        'rows' => '3',
                                        'maxlength' => TRUE,
                                        'class' => 'materialize-textarea',
                                    ]
                                )->label(GlobalConfigModule::t('gc', 'description')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?php
                                switch ($model->gwc_type) {
                                    case "text":
                                        /*Nested Switch for ....if  field is text*/
                                        switch ($model->gwc_key) {
                                            case 'minimum_available_disk_limit':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value',
                                                    [
                                                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon custom-width">MB</span></div>{error}',
                                                    ]
                                                )->textInput(
                                                    [
                                                        'type' => 'number',
                                                        'maxlength' => 2,
                                                        'min' => 1,
                                                        'max' => 50,
                                                    ]
                                                )->label(
                                                    GlobalConfigModule::t(
                                                        'gc',
                                                        'value'
                                                    ));
                                                break;
                                            case 'sip_reg_auto_refresh':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value',
                                                    [
                                                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon custom-width">Seconds</span></div>{error}',
                                                    ]
                                                )->textInput(['type' => 'number', 'min' => 1])->label(
                                                    GlobalConfigModule::t(
                                                        'gc',
                                                        'value'
                                                    ));
                                                break;
                                            case 'session_timeout':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value',
                                                    [
                                                        // 'template' => '{label}<div class="input-group">{input}<span class="">Minutes</span></div>{error}',
                                                    ]
                                                )->textInput(
                                                    [
                                                        'maxlength' => true
                                                    ]
                                                )->label(
                                                    GlobalConfigModule::t(
                                                        'gc',
                                                        'value_minutes'
                                                    ));
                                                break;
                                            case 'mail_send_from':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->textinput(['maxlength' => TRUE])->label(
                                                    GlobalConfigModule::t(
                                                        'gc',
                                                        'value'
                                                    ));
                                                break;
                                            case 'fs_server':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->textInput(['type' => 'text', 'maxlength' => TRUE])
                                                    ->label(
                                                        GlobalConfigModule::t(
                                                            'gc',
                                                            'value'
                                                        ));
                                                break;

                                            case 'default_server':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->textInput(['type' => 'text', 'maxlength' => TRUE])
                                                    ->label(
                                                        GlobalConfigModule::t(
                                                            'gc',
                                                            'value'
                                                        ));
                                                break;
                                            case 'wildcard_sip_domain':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->textInput(['type' => 'text', 'maxlength' => TRUE])
                                                    ->label(
                                                        GlobalConfigModule::t(
                                                            'gc',
                                                            'value'
                                                        ));
                                                break;
                                            case 'wildcard_web_domain':
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->textInput(['type' => 'text', 'maxlength' => TRUE])
                                                    ->label(
                                                        GlobalConfigModule::t(
                                                            'gc',
                                                            'value'
                                                        ));
                                                break;
                                            case 'HIGH_SPEED':
                                            case 'LOW_SPEED':
                                            case 'MEDIUM_SPEED':
                                                ?>
                                                <div class="col-sm-4">
                                                    <label><?php echo GlobalConfigModule::t(
                                                            'gc',
                                                            'gwc_value'
                                                        ) ?></label>
                                                    <select id="assignedAudioCodecs" multiple size="5"
                                                            class="form-control list"
                                                            data-target="avaliable">
                                                        <?php
                                                        if (isset($selectedCodecs)) {
                                                            foreach ($selectedCodecs as $key => $value) {
                                                                echo "<option value='" . $value . "'>" . $value
                                                                    . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php
                                                    echo $form->field(
                                                        $model,
                                                        'gwc_value'
                                                    )->hiddenInput(['id' => 'selectedAudioCodecs'])->label(
                                                        FALSE
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-sm-1 btn_div mt-3">
                                                    <button type="button" id="btnUpAudio"
                                                            data-target='avaliable'
                                                            class="btn btn-success btn-sm">
                                                        <i class="glyphicon glyphicon-chevron-up"></i>
                                                    </button>
                                                    <button type="button" id="btnDownAudio"
                                                            class="btn btn-danger btn-sm"
                                                            data-target='assigned'>
                                                        <i class="glyphicon glyphicon-chevron-down"></i>
                                                    </button>
                                                </div>
                                                <?php break; ?>
                                            <?php

                                            default:
                                                echo $form->field(
                                                    $model,
                                                    'gwc_value'
                                                )->label(
                                                    GlobalConfigModule::t(
                                                        'gc',
                                                        'value'
                                                    ));
                                        }
                                        break;
                                    case "textarea":
                                        echo $form->field(
                                            $model,
                                            'gwc_value'
                                        )->textarea(['rows' => '3', 'maxlength' => TRUE])->label(
                                            GlobalConfigModule::t(
                                                'gc',
                                                'gwc_key'
                                            ));
                                        break;
                                    case "dropdown":
                                        if ($model->gwc_key == "default_timezone"):
                                            echo
                                            $form->field(
                                                $model,
                                                'gwc_value',
                                                [
                                                    'template' => '<div class="tab-content"><div class="default-select2"><div class="element-margin-bottom form-bootstrap-select">{label}{input}</div></div></div>{error}',
                                                ]
                                            )->dropDownList(
                                                $model->getTimeZone(),
                                                [
                                                    'class' => 'timezone-select2',
                                                    'placeholder' => "Select TimeZone",
                                                    'allowClear' => TRUE,
                                                ]
                                            )->label(
                                                GlobalConfigModule::t(
                                                    'gc',
                                                    'value'
                                                ));
                                        endif;
                                        if ($model->gwc_key == "default_fax_gateway"):
                                            $faxTrunkList = TrunkMaster::getFaxTrunk();

                                            echo $form->field(
                                                $model,
                                                'gwc_value'
                                            )->DropDownList(
                                                $faxTrunkList,
                                                [
                                                    'data-style' => "btn-secondary",
                                                    'prompt' => GlobalConfigModule::t(
                                                        'gc',
                                                        'select_fax_gateway'
                                                    ),
                                                    'id' => 'select_file_name',
                                                ]
                                            )->label(
                                                GlobalConfigModule::t(
                                                    'gc',
                                                    'value'
                                                ));
                                        endif;
                                        break;
                                    case "radio":
                                        echo $form->field(
                                            $model,
                                            'gwc_value'
                                        )->radio(
                                            [
                                                'value' => $model->cfgvalue,
                                                'label' => $model->cfgvalue,
                                            ]
                                        )->label(
                                            GlobalConfigModule::t(
                                                'gc',
                                                'value'
                                            ));
                                        break;
                                    case "checkbox":
                                        echo $form->field(
                                            $model,
                                            'gwc_value'
                                        )->checkbox(
                                            [
                                                'value' => $model->cfgvalue,
                                                'label' => $model->cfgvalue,
                                                'uncheck' => NULL,
                                            ]
                                        )->label(
                                            GlobalConfigModule::t(
                                                'gc',
                                                'value'
                                            ));
                                        break;
                                    case "FILE":
                                        echo '<div class="file-field input-field" id="inputfile_">
                                <div class="btn">
                                    <span>'.Yii::t('app', 'file').'</span>'.
                                 $form->field($model, 'gwc_value',
                                    ['options' => ['class' => '']]
                                )->fileInput(['accept' => '.mp3, .wav'])->label(false).'
                            </div>&nbsp;<span style="color: red;">*</span>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text"
                                       value="'. array_reverse(explode('/', $model->gwc_value))[0] .'">
                            </div>
                            
                        </div>';
                                        break;
                                    default:
                                        echo $form->field(
                                            $model,
                                            'gwc_value'
                                        )->textInput(
                                            [
                                                'maxlength' => TRUE,
                                            ]
                                        )->label(
                                            GlobalConfigModule::t(
                                                'gc',
                                                'value'
                                            ));
                                }
                                ?>
                                <?php if ($model->gwc_key == 'moh_file') { ?>
                                    <div class="col s12">
                                        <span class="new badge red"
                                              data-badge-caption="Only MP3, Wav format is supported."></span>
                                    </div>
                                    <div class="input-field">
                                        <?php
                                        $gwc_value = $model->gwc_value;
                                        try {

                                            /*  $sourcePath = Url::to(
                                                  Yii::$app->params['adminStoragePath']
                                                  . 'moh/default/' . $gwc_value
                                              );*/

                                            $end = explode('/', $gwc_value);
                                            $end = array_reverse($end)[0];

                                            $sourcePath = Url::to('@web' . '/media/audio-libraries/' . $GLOBALS['tenantID'] . '/moh/default/' . $end);

                                        } catch (Exception $e) {
                                        }

                                        echo '<audio id ="player" controls  controlslist="nodownload"><source id ="wav_src" src="'
                                            . $sourcePath . '" ></audio>';
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(
                    GlobalConfigModule::t('gc', 'cancel'),
                    [
                        'index',
                        'page' => Yii::$app->session->get('page'),
                    ],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(
                    $model->isNewRecord ? GlobalConfigModule::t('gc', 'create')
                        : GlobalConfigModule::t('gc', 'update'),
                    [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4'
                            : 'btn waves-effect waves-light cyan accent-8',
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    $('#select_file_name').change(function () {
        var file_value = $("#select_file_name").val();
        var filepath = "<?php echo Url::to(
            Yii::$app->params['tenantStoragePath'] . 'audio-libraries/'
        ); ?>";
        var file_path = filepath + file_value;
        var audio = $("#player");
        if (file_path) {
            var ok = $('#wav_src').attr('src', file_path);
            audio.load();
            // audio.play();
        }
    });
</script>