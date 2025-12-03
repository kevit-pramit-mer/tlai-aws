<?php
namespace app\modules\ecosmob\crm\controllers;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\crm\assets\CrmAsset;
use app\modules\ecosmob\crm\models\AgentDispositionMapping;
use app\modules\ecosmob\crm\models\LeadCommentMapping;
use app\modules\ecosmob\crm\models\LeadGroupMember;
use app\modules\ecosmob\crm\models\LeadGroupMemberSearch;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

CrmAsset::register($this);
$selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : '0';
$selectedCampaign = $selectedCampaignData;
$agentId = Yii::$app->user->identity->adm_id;
if ($selectedCampaignData > 0) {
    $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

    if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Inbound') {

        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    }
    $dispotionData = isset($dispotionData) ? $dispotionData : '';

    $disposionList = DispositionType::find()->select(['ds_type_id'])->where(['ds_id' => $dispotionData])->asArray()->all();
    $disposionList = isset($disposionList) ? $disposionList : '';

    $disposionIds = implode(",", array_map(function ($a) {
        return implode("~", $a);
    }, $disposionList));

    $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
        ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
        ->asArray()->all();

    $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

    $searchModel = new LeadGroupMemberSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $scrdataProvider = $searchModel->scrsearch(Yii::$app->request->queryParams);
    $extentationNumber = $_SESSION['extentationNumber'];
    $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extentationNumber])->one();

    $crmList = new LeadGroupMember();
    $progresiveDataList = new LeadGroupMember();
    $LeadComment = new LeadCommentMapping();
    $agentDispoMapping = new AgentDispositionMapping();

    $selectedCampaign = $_SESSION['selectedCampaign'];

    $campaignData = Campaign::find()->select(['cmp_caller_id', 'cmp_caller_name'])->where(['cmp_id' => $selectedCampaign])->asArray()->one();

    $searchModel = $searchModel;
    $crmList = $crmList;
    $progresiveDataList = $progresiveDataList;
    $leadCommentMapping = $LeadComment;
    $dataProvider = $dataProvider;
    $scrdataProvider = $scrdataProvider;
    $extensionInformation = $extensionInformation;
    $campaignDialerType = $campaignDialerType;
    $disposionListType = $disposionListType;
    $agentDispoMapping = $agentDispoMapping;
    $selectedCampaign = $selectedCampaign;
    $agentId = $agentId;
    $campaignData = $campaignData;
} else {
    return false;
}
?>
<script type="text/javascript">
    var extensionNumber = '<?php echo isset($extensionInformation) ? $extensionInformation['em_extension_number'] : ''; ?>'
    var extensionPassword = '<?php echo isset($extensionInformation) ? $extensionInformation['em_password'] : ''; ?>'
    var extensionName = '<?php echo isset($extensionInformation) ? $extensionInformation['em_extension_name'] : ''; ?>'
    var campaign_id = '<?php echo isset($selectedCampaign) ? $selectedCampaign : ''; ?>'
    var agent_id = '<?php echo isset($agentId) ? $agentId : ''; ?>'
    var campaignDialerType = '<?php echo isset($campaignDialerType) ? $campaignDialerType['cmp_dialer_type'] : ''; ?>'
    var campaignType = '<?php echo isset($campaignDialerType) ? $campaignDialerType['cmp_type'] : ''; ?>'
    var ringFile = '<?php echo Url::to('@web' . '/theme/sound/bell_ring2.mp3'); ?>'
    var cmp_caller_id = '<?php echo isset($campaignData) ? $campaignData['cmp_caller_id'] : ''; ?>';
    var cmp_caller_name = '<?php echo isset($campaignData) ? $campaignData['cmp_caller_name'] : ''; ?>';
    var no_more_lead_in_hopper = "<h6 class='pl-3'>No more leads in the hopper for campaign</h6>";
    var mute = "Mute";
    var unmute = "Un Mute";
    var hold = "Hold";
    var unhold = "Un hold";
</script>







