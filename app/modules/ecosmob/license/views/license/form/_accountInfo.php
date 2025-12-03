<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\license\LicenseModule;
use app\components\CommonHelper;
use app\models\TenantModuleConfig;
use app\modules\ecosmob\license\models\LicenseTicketManagement;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchModel LicenseTicketManagement */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $licenseTicketModal LicenseTicketManagement */
/* @var $data */
/* @var $permissions */

$permissions = $GLOBALS['permissions'];
?>

<div class="row">
    <div class="col s12">
        <div class="card table-structure">
            <div class="card-content">
                <div class="card-header d-flex align-items-center justify-content-between w-100">
                    <div class="header-title pl-1">
                        <?= LicenseModule::t('app', 'account_details') ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col s4 mb-2 p-0">
                        <table class="account-details-table">
                            <tr>
                                <td><?= LicenseModule::t('app', 'account_code') ?> :</td>
                                <td><?= Yii::$app->session->get('tenant_code') ?></td>
                            </tr>
                            <?php if(TenantModuleConfig::isTrunkDidRoutingEnabled() == true) { ?>
                            <tr>
                                <td><?= LicenseModule::t('app', 'allocated_dids') ?> :</td>
                                <td><?= $data['licenseData']['maxDID'] ?></td>
                            </tr>
                                <tr>
                                    <td><?= LicenseModule::t('app', 'license_name') ?> :</td>
                                    <td><?= $data['licenseData']['licenseName'] ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12">
        <?php $form = ActiveForm::begin(
            [
                'class' => 'row',
                'method' => 'post',
                'id' => 'license-active-form',
                'action' => Yii::$app->urlManager->createUrl(
                    '/license/license/index'
                ),
                'fieldConfig' => [
                    'options' => [
                        'class' => 'input-field ',
                    ],
                ],
            ]
        ); ?>
        <div class="card table-structure">
            <div class="card-content">
                <div class="card-header d-flex align-items-center justify-content-between w-100">
                    <div class="header-title pl-1">
                        <?= LicenseModule::t('app', 'license_management') ?>
                    </div>
                </div>
                <div class="card-body license-card">
                    <div class="col s8 mb-2">
                        <table class="license-manage-table">
                            <thead>
                            <tr>
                                <th><?= LicenseModule::t('app', 'entity') ?></th>
                                <th class="non-edit-allo"><?= LicenseModule::t('app', 'allocated') ?></th>
                                <th class="edit-allo d-none"><?= LicenseModule::t('app', 'allocated') ?></th>
                                <th><?= LicenseModule::t('app', 'used') ?></th>
                                <th><?= LicenseModule::t('app', 'free') ?></th>
                            </tr>
                            </thead>
                            <tr>
                                <td><?= Yii::t('app', 'extension') ?></td>
                                <td class="non-edit-allo"><?= $data['licenseData']['maxExtensions'] ?></td>
                                <td class="edit-allo d-none">
                                    <div class="number-input">
                                        <button type="button" class="btn" onclick="changeValue('maxExtensions', -1)">-</button>
                                        <input type="number" name="maxExtensions" id="maxExtensions" value="<?= $data['licenseData']['maxExtensions'] ?>" min="1" max="999">
                                        <button type="button" class="btn" onclick="changeValue('maxExtensions', 1)">+</button>
                                    </div>
                                </td>
                                <td><?= $data['usedExt'] ?></td>
                                <td><?= $data['freeExt'] ?></td>
                            </tr>
                            <tr>
                                <td><?= Yii::t('app', 'agent') ?></td>
                                <td class="non-edit-allo"><?= $data['licenseData']['maxAgents'] ?></td>
                                <!--<td class="edit-allo d-none"><input type="number" name="maxAgents" id="maxAgents"
                                           value="<?php /*= $data['licenseData']['maxAgents'] */?>" min="1" max="999"></td>-->
                                <td class="edit-allo d-none">
                                    <div class="number-input">
                                        <button type="button" class="btn" onclick="changeValue('maxAgents', -1)">-</button>
                                        <input type="number" name="maxAgents" id="maxAgents" value="<?= $data['licenseData']['maxAgents'] ?>" min="1" max="999">
                                        <button type="button" class="btn" onclick="changeValue('maxAgents', 1)">+</button>
                                    </div>
                                </td>
                                <td><?= $data['usedAgent'] ?></td>
                                <td><?= $data['freeAgent'] ?></td>
                            </tr>
                            <tr>
                                <td><?= Yii::t('app', 'supervisors') ?></td>
                                <td class="non-edit-allo"><?= $data['licenseData']['maxSupervisors'] ?></td>
                                <td class="edit-allo d-none">
                                    <div class="number-input">
                                        <button type="button" class="btn" onclick="changeValue('maxSupervisors', -1)">-</button>
                                        <input type="number" name="maxSupervisors" id="maxSupervisors" value="<?= $data['licenseData']['maxSupervisors'] ?>" min="1" max="999">
                                        <button type="button" class="btn" onclick="changeValue('maxSupervisors', 1)">+</button>
                                    </div>
                                </td>
                                <td><?= $data['usedSup'] ?></td>
                                <td><?= $data['freeSup'] ?></td>
                            </tr>
                            <?php if(TenantModuleConfig::isTrunkDidRoutingEnabled() == true) { ?>
                            <tr>
                                <td><?= Yii::t('app', 'trunk') ?></td>
                                <td class="non-edit-allo"><?= $data['licenseData']['maxSipTrunk'] ?></td>
                                <td class="edit-allo d-none">
                                    <div class="number-input">
                                        <button type="button" class="btn" onclick="changeValue('maxSipTrunk', -1)">-</button>
                                        <input type="number" name="maxSipTrunk" id="maxSipTrunk" value="<?= $data['licenseData']['maxSipTrunk'] ?>" min="1" max="999">
                                        <button type="button" class="btn" onclick="changeValue('maxSipTrunk', 1)">+</button>
                                    </div>
                                </td>
                                <td><?= $data['usedTrunk'] ?></td>
                                <td><?= $data['freeTrunk'] ?></td>
                            </tr>
                            <tr>
                                <td><?= Yii::t('app', 'did') ?></td>
                                <td class="non-edit-allo"><?= $data['licenseData']['maxDID'] ?></td>
                                <td class="edit-allo d-none">
                                    <div class="number-input">
                                        <button type="button" class="btn" onclick="changeValue('maxDID', -1)">-</button>
                                        <input type="number" name="maxDID" id="maxDID" value="<?= $data['licenseData']['maxDID'] ?>" min="1" max="999">
                                        <button type="button" class="btn" onclick="changeValue('maxDID', 1)">+</button>
                                    </div>
                                </td>
                                <td><?= $data['usedDid'] ?></td>
                                <td><?= $data['freeDid'] ?></td>
                            </tr>
                                <tr>
                                    <td><?= LicenseModule::t('app', 'concurrent_calls') ?></td>
                                    <td class="non-edit-allo"><?= $data['licenseData']['concurrentCalls'] ?></td>
                                    <td class="edit-allo d-none">
                                        <div class="number-input">
                                            <button type="button" class="btn" onclick="changeValue('concurrentCalls', -1)">-</button>
                                            <input type="number" name="concurrentCalls" id="concurrentCalls" value="<?= $data['licenseData']['concurrentCalls'] ?>" min="1" max="999">
                                            <button type="button" class="btn" onclick="changeValue('concurrentCalls', 1)">+</button>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            <?php } ?>
                        </table>
                        <input type="hidden" name="ticket_status" id="ticket_status" value="Open">
                    </div>
                    <?php if (in_array('/license/license/change-status', $permissions)){ ?>
                    <div class="col s12 d-flex mb-2 license-form-btn">
                        <?= Html::a(LicenseModule::t('app', 'cancel'),
                            ['index', 'page' => Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 mr-1 lice-cancel-btn d-none']) ?>
                        <?= Html::button(
                            LicenseModule::t('app', 'modify'),
                            ['id' => 'changeBtn', 'class' => 'btn waves-effect waves-light cyan modify-btn']
                        ) ?>
                        <?= Html::submitButton(
                            LicenseModule::t('app', 'raise_request'),
                            ['id' => 'changeBtn', 'class' => 'btn waves-effect waves-light cyan d-none sub-btn']
                        ) ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <!--<div class="col s12">
        <div class="card table-structure">
            <div class="card-content">
                <div class="card-header d-flex align-items-center justify-content-between w-100">
                    <div class="header-title pl-1">
                        <?php /*= LicenseModule::t('app', 'license_modification_tickets') */?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2 ticket-details-table-scroll">
                        <div class="col s12">
                            <table>
                                <thead>
                                <tr>
                                    <th><?php /*= LicenseModule::t('app', 'ticket_id') */?></th>
                                    <th><?php /*= LicenseModule::t('app', 'date') */?></th>
                                    <th><?php /*= LicenseModule::t('app', 'request_type') */?></th>
                                    <th><?php /*= LicenseModule::t('app', 'status') */?></th>
                                    <th><?php /*= LicenseModule::t('app', 'details') */?></th>
                                </tr>
                                </thead>
                                <?php /*foreach ($licenseTicketModal as $_licenseTicketModal) {
                                    $status = [];
                                    $status[$_licenseTicketModal->status] = $_licenseTicketModal->status;
                                    if ($_licenseTicketModal->status == 'On-hold') {
                                        $status['Open'] = 'Open';
                                        $status['Cancelled'] = 'Cancel';
                                    }
                                    if ($_licenseTicketModal->status == 'Open') {
                                        $status['Cancelled'] = 'Cancel';
                                    }
                                    if ($_licenseTicketModal->status == 'Cancelled') {
                                        $status['Cancelled'] = 'Cancelled';
                                    }
                                    */?>
                                    <tr>
                                        <td><?php /*= $_licenseTicketModal->ticket_unique_id */?></td>
                                        <td><?php /*= CommonHelper::tsToDt($_licenseTicketModal->created_at) */?></td>
                                        <td><?php /*= LicenseModule::t('app', 'license_modification') */?></td>

                                        <?php /*if (in_array('/license/license/change-status', $permissions)){ */?>
                                        <td><?php /*= HTML::dropDownList('status', $_licenseTicketModal->status, $status, [
                                                'class' => 'change-status',
                                                'data-id' => $_licenseTicketModal->id,
                                                'data-requested' => $_licenseTicketModal->requested
                                            ])*/?></td>
                                        <?php /*}else{ */?>
                                            <td><?php /*= $_licenseTicketModal->status */?></td>
                                        <?php /*} */?>
                                        <td>
                                            <?php /*= Html::a('<span class="material-icons">visibility</span>', 'javascript:void(0);',
                                                ['class' => 'view_details', 'title' => 'View Details',
                                                    'data-requested' => $_licenseTicketModal->requested,
                                                    'data-allocated' => $_licenseTicketModal->allocated
                                                ]) */?>
                                        </td>
                                    </tr>
                                <?php /*} */?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <?php Pjax::begin(['id' => 'ticket-index', 'timeout' => 0, 'enablePushState' => false]); ?>
        <div class="col s12">
            <div class="profile-contain">
                <div class="section section-data-tables">
                    <div class="row">
                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title">
                                            <?= LicenseModule::t('app', 'license_modification_tickets') ?>
                                        </div>
                                    </div>
                                    <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                        <?php try {
                                            echo GridView::widget([
                                                'id' => 'ticket-grid-index',
                                                // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str_license(),
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                    'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                    'maxButtonCount' => 5,
                                                ],
                                                'options' => [
                                                    'tag' => FALSE,
                                                ],
                                                'columns' => [
                                                    [
                                                        'attribute' => 'ticket_unique_id',
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'enableSorting' => True,
                                                    ],
                                                    [
                                                        'attribute' => 'created_at',
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            return CommonHelper::tsToDt($model->created_at);
                                                        },
                                                    ],
                                                    [
                                                        'header' => LicenseModule::t('app', 'request_type'),
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            return LicenseModule::t('app', 'license_modification');
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'status',
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'enableSorting' => True,
                                                        'format' => 'raw',
                                                        'value' => function ($model) use($permissions) {
                                                            if (in_array('/license/license/change-status', $permissions)) {
                                                                $status = [];
                                                                $status[$model->status] = $model->status;
                                                                if ($model->status == 'On-hold') {
                                                                    $status['Open'] = 'Open';
                                                                    $status['Cancelled'] = 'Cancel';
                                                                }
                                                                if ($model->status == 'Open') {
                                                                    $status['Cancelled'] = 'Cancel';
                                                                }
                                                                if ($model->status == 'Cancelled') {
                                                                    $status['Cancelled'] = 'Cancelled';
                                                                }
                                                                return Html::dropDownList('status', $model->status, $status, [
                                                                    'class' => 'change-status',
                                                                    'data-id' => $model->id,
                                                                    'data-requested' => $model->requested,
                                                                ]);
                                                            } else {
                                                                return $model->status;
                                                            }
                                                        },
                                                    ],
                                                    [
                                                        'class' => 'yii\grid\ActionColumn',
                                                        'template' => '{view}',
                                                        'header' => LicenseModule::t('app', 'details'),
                                                        'headerOptions' => ['class' => 'center width-10'],
                                                        'contentOptions' => ['class' => 'center width-10'],
                                                        'buttons' => [
                                                            'view' => function (
                                                                $url, $model
                                                            ){

                                                                return Html::a('<i class="material-icons">visibility</i>', 'javascript:void(0);',
                                                                    ['class' => 'view_details', 'title' => 'View Details',
                                                                        'data-requested' => $model->requested,
                                                                        'data-allocated' => $model->allocated,
                                                                        'data-pjax' => 0,
                                                                    ]);
                                                                }
                                                        ]
                                                    ],
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline',
                                                ],
                                            ]);
                                        } catch (Exception $e) {
                                            print_r($e->getMessage());exit;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php Pjax::end(); ?>

</div>

<div id="request-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo LicenseModule::t('app', 'reminder') ?></h5>
        </div>
        <div class="modal-body">
            <div class="col s12">
                <p class="ext-msg"></p>
                <p class="agent-msg"></p>
                <p class="supervisor-msg"></p>
                <p class="trunk-msg"></p>
                <p class="did-msg"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button"
                    class="btn waves-effect waves-light bg-gray-200 modal-close"><?= LicenseModule::t('app', 'close') ?></button>
            <button type="button" class="btn btn-primary ticket-btn" data-dismiss="modal"
                    aria-hidden="true"><?= LicenseModule::t('app', 'continue') ?>
            </button>
        </div>
    </div>
</div>

<div id="confirm-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo LicenseModule::t('app', 'confirmation') ?></h5>
        </div>
        <div class="modal-body">
            <div class="col s12 req-on-hold">
                <p><?= LicenseModule::t('app', 'reqOnHoldConfirmMsg') ?></p>
            </div>
            <div class="col s12 req-open">
                <p><?= LicenseModule::t('app', 'reqOpenConfirmMsg') ?></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button"
                    class="btn waves-effect waves-light bg-gray-200 modal-close"><?= LicenseModule::t('app', 'cancel') ?></button>
            <button type="button" class="btn btn-primary confirm-btn" data-dismiss="modal"
                    aria-hidden="true"><?= LicenseModule::t('app', 'yes') ?>
            </button>
        </div>
    </div>
</div>

<div id="ticket-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo LicenseModule::t('app', 'ticket_details') ?></h5>
        </div>
        <div class="modal-body mt-2">
            <div class="col s12 ticket-details">
                <table>
                    <thead>
                    <tr>
                        <th><?= LicenseModule::t('app', 'entity') ?></th>
                        <th><?= LicenseModule::t('app', 'allocated') ?></th>
                        <th><?= LicenseModule::t('app', 'requested') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button"
                    class="btn waves-effect waves-light bg-gray-200 modal-close"><?= LicenseModule::t('app', 'close') ?></button>
        </div>
    </div>
</div>

<div id="request-again-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="request-title"><?php echo LicenseModule::t('app', 'reminder') ?></h5>
        </div>
        <div class="modal-body">
            <div class="col s12">
                <p class="rext-msg"></p>
                <p class="ragent-msg"></p>
                <p class="rsupervisor-msg"></p>
                <p class="rtrunk-msg"></p>
                <p class="rdid-msg"></p>
                <p class="confirm-msg"></p>
                <input type="hidden" name="ticket_id" id="ticket_id" value="">
                <input type="hidden" name="changed_ticket_status" id="changed_ticket_status" value="">
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::a(LicenseModule::t('app', 'close'),
                ['index', 'page' => Yii::$app->session->get('page')],
                ['class' => 'btn waves-effect waves-light bg-gray-200 modal-close']) ?>
           <!-- <button type="button"
                    class="btn waves-effect waves-light bg-gray-200 modal-close"><?php /*= LicenseModule::t('app', 'close') */?></button>-->
            <button type="button" class="btn btn-primary reconfirm-btn d-none" data-dismiss="modal"
                    aria-hidden="true"><?= LicenseModule::t('app', 'yes') ?>
            </button>
        </div>
    </div>
</div>

<div id="loader" style="display: none;">
    <div class="spinner"></div>
</div>

<style>
    #loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .spinner {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    var usedExt = '<?= $data['usedExt'] ?>';
    var usedAgent = '<?= $data['usedAgent'] ?>';
    var usedSup = '<?= $data['usedSup'] ?>';
    var usedTrunk = '<?= $data['usedTrunk'] ?>';
    var usedDid = '<?= $data['usedDid'] ?>';
    function changeValue(id, delta) {
        const input = document.getElementById(id);
        let currentValue = parseInt(input.value);
        let newValue = currentValue + delta;

        if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
            input.value = newValue;
        }
    }
    $(document).ready(function () {
        $.pjax.reload({container: '#ticket-index', async: false});
        // Initialize modals
        $('.modal').modal({
            dismissible: false // Consider removing this option if it causes issues
        });

        $('.ext-msg').text('');
        $('.agent-msg').text('');
        $('.supervisor-msg').text('');
        $('.trunk-msg').text('');
        $('.did-msg').text('');

        $('.rext-msg').text('');
        $('.ragent-msg').text('');
        $('.rsupervisor-msg').text('');
        $('.rtrunk-msg').text('');
        $('.rdid-msg').text('');

        $('.modify-btn').removeClass('d-none');
        $('.sub-btn').addClass('d-none');
        $('input[type="number"]').prop('disabled', true);
        $('.non-edit-allo').removeClass('d-none');
        $('.edit-allo').addClass('d-none');
        $('.lice-cancel-btn').addClass('d-none');

        $(document).on('click', '.modify-btn', function (e) {
            $('.modify-btn').addClass('d-none');
            $('.sub-btn').removeClass('d-none');
            $('input[type="number"]').prop('disabled', false);
            $('.non-edit-allo').addClass('d-none');
            $('.edit-allo').removeClass('d-none');
            $('.lice-cancel-btn').removeClass('d-none');
        });

        $(document).on('click', '.sub-btn', function (e) {
            e.preventDefault();

            var count = 0;

            $('.ext-msg').text('');
            $('.agent-msg').text('');
            $('.supervisor-msg').text('');
            $('.trunk-msg').text('');
            $('.did-msg').text('');

            if (parseInt($('#maxExtensions').val()) < parseInt(usedExt)) {
                count++;
                $('.ext-msg').text("Error: The number of currently in-use extensions exceeds the requested number. Please release extra extensions to proceed.");
            }
            if (parseInt($('#maxAgents').val()) < parseInt(usedAgent)) {
                count++;
                $('.agent-msg').text("Error: The number of currently in-use agents exceeds the requested number. Please release extra agents to proceed.");
            }
            if (parseInt($('#maxSupervisors').val()) < parseInt(usedSup)) {
                count++;
                $('.supervisor-msg').text("Error: The number of currently in-use supervisors exceeds the requested number. Please release extra supervisors to proceed.");
            }
            if (parseInt($('#maxSipTrunk').val()) < parseInt(usedTrunk)) {
                count++;
                $('.trunk-msg').text("Error: The number of currently in-use trunks exceeds the requested number. Please release extra trunks to proceed.");
            }
            if (parseInt($('#maxDID').val()) < parseInt(usedDid)) {
                count++;
                $('.did-msg').text("Error: The number of currently in-use DIDs exceeds the requested number. Please release extra DIDs to proceed.");
            }
            if (count > 0) {
                $('#ticket_status').val('On-hold');
                $('.req-on-hold').removeClass('d-none');
                $('.req-open').addClass('d-none');
                $('#request-modal').modal('open');
            } else {
                $('#ticket_status').val('Open');
                $('.req-on-hold').addClass('d-none');
                $('.req-open').removeClass('d-none');
                $("#confirm-modal").modal('open');
            }
        });
        $(document).on('click', '.ticket-btn', function (e) {
            $('#request-modal').modal('close');
            $("#confirm-modal").modal('open');
        });
        $(document).on('click', '.confirm-btn', function (e) {
            $('#confirm-modal').modal('close');
            $('#license-active-form').submit();
        });
        $(document).on('click', '.view_details', function (e) {
            var req_data = JSON.parse($(this).attr('data-requested'));
            var allocated_data = JSON.parse($(this).attr('data-allocated'));
            var tableBody = $('#ticket-modal .ticket-details table tbody'); // Target tbody

            tableBody.empty(); // Clear existing rows if any

            $.each(req_data, function (key, value) {
                var allocated_value = allocated_data[key] !== undefined ? allocated_data[key] : '';
                tableBody.append('<tr><td>' + key + '</td><td>' + allocated_value + '</td><td>' + value + '</td></tr>');
            });

            $('#ticket-modal').modal('open');
        });

        $(document).on('change', '.change-status', function (e) {
            var req_data = JSON.parse($(this).attr('data-requested'));
            $('#ticket_id').val($(this).attr('data-id'));
            $('#changed_ticket_status').val($(this).val());
            var count = 0;
            $('.rext-msg').text('');
            $('.ragent-msg').text('');
            $('.rsupervisor-msg').text('');
            $('.rtrunk-msg').text('');
            $('.rdid-msg').text('');
            if($(this).val() != 'Cancelled') {
                $.each(req_data, function (key, value) {
                    if (key == 'Extension') {
                        if (parseInt(value) < usedExt) {
                            count++;
                            $('.rext-msg').text("Error: The number of currently in-use extensions exceeds the requested number. Please release extra extensions to proceed.");
                        }
                    }
                    if (key == 'Agents') {
                        if (parseInt(value) < usedAgent) {
                            count++;
                            $('.ragent-msg').text("Error: The number of currently in-use agents exceeds the requested number. Please release extra agents to proceed.");
                        }
                    }
                    if (key == 'Supervisor') {
                        if (parseInt(value) < usedSup) {
                            count++;
                            $('.rsupervisor-msg').text("Error: The number of currently in-use supervisors exceeds the requested number. Please release extra supervisors to proceed.");
                        }
                    }
                    if (key == 'Trunk') {
                        if (parseInt(value) < usedTrunk) {
                            count++;
                            $('.rtrunk-msg').text("Error: The number of currently in-use trunks exceeds the requested number. Please release extra trunks to proceed.");
                        }
                    }
                    if (key == 'DID') {
                        if (parseInt(value) < usedDid) {
                            count++;
                            $('.rdid-msg').text("Error: The number of currently in-use DIDs exceeds the requested number. Please release extra DIDs to proceed.");
                        }
                    }
                });
            }
            if (count > 0) {
                $('.reconfirm-btn').addClass('d-none');
            } else {
                $('.reconfirm-btn').removeClass('d-none');
                $('.confirm-msg').text("Are you sure you want to change a ticket status?");
            }
            $('#request-again-modal').modal('open');
        });

        $(document).on('click', '.reconfirm-btn', function (e) {
            $('#request-again-modal').modal('close');
            $('#loader').show();
            $.ajax({
                url: baseURL + "index.php?r=license/license/change-status",
                data: {'id': $('#ticket_id').val(), 'status': $('#changed_ticket_status').val()},
                type: 'POST',
                success: function (result) {
                },
                complete: function () {
                    $('#loader').hide();
                }
            });
        });
    });
</script>