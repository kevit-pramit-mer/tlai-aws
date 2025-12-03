<?php

namespace app\modules\ecosmob\extension\models;

use app\components\CommonHelper;
use app\modules\ecosmob\cdr\CdrModule;
use Yii;
use yii\helpers\Url;
use yii\mongodb\ActiveRecord;

class ExtensionCallLog extends ActiveRecord
{

    public $isfile;

    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'uctenant.cdr'];
    }

    public function rules()
    {
        return [
            [
                [
                    "uuid",
                    "sip_call_id",
                    "dialed_number",
                    "caller_id_number",
                    "record_filename",
                    "call_type",
                    "start_epoch",
                    "answer_epoch",
                    "end_epoch",
                    "callstatus",
                    "direction",
                    "duration",
                    "billsec",
                    "trunk_id",
                    "trunk_name",
                    "hangup",
                    "isfile",
                ],
                'safe',
            ],
        ];
    }

    public function attributes()
    {
        return [
            "_id",
            "uuid",
            "sip_call_id",
            "dialed_number",
            "caller_id_number",
            "record_filename",
            "call_type",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "callstatus",
            "direction",
            "duration",
            "billsec",
            "ext_call",
            "trunk_id",
            "trunk_name",
            "hangup",
            "isfile",
        ];
    }

    public function attributeLabels()
    {
        return [
            'uuid' => CdrModule::t('cdr', 'uuid'),
            'sip_call_id' => CdrModule::t('cdr', 'sip_call_id'),
            'dialed_number' => CdrModule::t('cdr', 'dialed_number'),
            'caller_id_number' => CdrModule::t('cdr', 'caller_id_number'),
            'isfile' => CdrModule::t('cdr', 'isfile'),
            'call_type' => CdrModule::t('cdr', 'call_type'),
            'start_epoch' => CdrModule::t('cdr', 'start_epoch'),
            'answer_epoch' => CdrModule::t('cdr', 'answer_epoch'),
            'end_epoch' => CdrModule::t('cdr', 'end_epoch'),
            'callstatus' => CdrModule::t('cdr', 'callstatus'),
            'direction' => CdrModule::t('cdr', 'direction'),
            'duration' => CdrModule::t('cdr', 'duration'),
            'billsec' => CdrModule::t('cdr', 'billsec'),
            'ext_call' => CdrModule::t('cdr', 'ext_call'),
            'trunk_id' => CdrModule::t('cdr', 'trunk_id'),
            'trunk_name' => CdrModule::t('cdr', 'trunk_name'),
            'hangup' => CdrModule::t('cdr', 'hangup'),
        ];
    }

    public static function getAllCall(){
        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);

        $videoCall = $callSetting_data->ecs_video_calling;
        $html = '';
        $extension = ExtensionCdr::find()
            ->andWhere(['!=', 'from_number', ''])
            ->andWhere(['!=', 'to_number', ''])
            ->andWhere(['or',
                ['from_number' => Yii::$app->user->identity->em_extension_number],
                ['to_number' => Yii::$app->user->identity->em_extension_number]
            ])->orderBy('start_time DESC')->all();
        if(!empty($extension)) {
            foreach ($extension as $_extension) {
                $callDate = $img = $displayNumber = '';
                $displayNumber = ($_extension->to_number == Yii::$app->user->identity->em_extension_number ? $_extension->from_number : $_extension->to_number);
                $dateTime = CommonHelper::tsToDt($_extension->start_time);
                $date = date('Y-m-d', strtotime($dateTime));
                $talkTime = ((!empty($_extension->ans_time) && !empty($_extension->end_time)) ? date('H:i:s', strtotime($_extension->end_time) - strtotime($_extension->ans_time)) : '00:00:00');
                if ($date == date('Y-m-d')) {
                    $callDate = "Today";
                } elseif($date == date('Y-m-d', strtotime('-1 day'))) {
                    $callDate = 'Yesterday';
                } elseif($date == date('Y-m-d', strtotime('+1 day'))) {
                    $callDate = 'Tomorrow';
                } else {
                    $callDate = date('Y-m-d', strtotime($dateTime));
                }
                if(empty($_extension->ans_time) && $_extension->to_number == Yii::$app->user->identity->em_extension_number){
                    if($_extension->call_type == 'video') {
                        $img = '<img src="' . Url::base(true) . '/theme/assets/images/missed-videocall-icon.png" />';
                    }else{
                        $img = '<img src="' . Url::base(true) . '/theme/assets/images/missed-call-icon.png" />';
                    }
                }elseif (!empty($_extension->ans_time) && $_extension->to_number == Yii::$app->user->identity->em_extension_number){
                    if($_extension->call_type == 'video') {
                        $img = ' <img src="' . Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png" />';
                    }elsE{
                        $img = ' <img src="' . Url::base(true) . '/theme/assets/images/incoming-call-icon.png" />';
                    }
                }elseif ($_extension->from_number == Yii::$app->user->identity->em_extension_number){
                    if($_extension->call_type == 'video') {
                        $img = '<img src="' . Url::base(true) . '/theme/assets/images/outgoing-videocall-icon.png" />';
                    }else{
                        $img = '<img src="' . Url::base(true) . '/theme/assets/images/outgoing-call.png" />';
                    }
                }
                $html .= '
                <li>
                    <div class="collapsible-header">
                        <div class="call-lists">'
                            .$img.'
                            <div>
                                <p class="caller-name">'.$displayNumber.'</p>
                                <p class="caller-time">'.date('h:i A', strtotime($dateTime)).'</p>
                            </div>
                            <div class="ml-auto">
                                <div class="caller-date">'.$callDate.'</div>
                                <p class="mb-0 call-time">'.$talkTime.'</p>
                           </div>
                        </div>
                    </div>
                     <div class="collapsible-body">
                        <div class="call-opiton">
                             <i class="material-icons dial-pad-open" id="audioCall" data-number="'.$displayNumber.'">local_phone</i>';
                        if($videoCall){
                            $html .= '<i class="material-icons videocall-enable" id="videoCall" data-number="'.$displayNumber.'">videocam</i>';
                        }else{
                            $html .= '<i class="material-icons cursor-auto">videocam_off</i>';
                        }
                        $html .= '</div>
                    </div>
                </li>';
            }
        }
        return $html;
    }

    public static function getMissCall(){
        $html = '';
        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);

        $videoCall = $callSetting_data->ecs_video_calling;
        $extension = ExtensionCdr::find()
            ->andWhere(['!=', 'from_number', ''])
            ->andWhere(['!=', 'to_number', ''])
            ->andWhere(['to_number' => Yii::$app->user->identity->em_extension_number])
            ->andWhere(['IS', 'ans_time', null])
            ->orderBy('start_time DESC')->all();
        if(!empty($extension)) {
            foreach ($extension as $_extension) {
                $callDate = '';
                $dateTime = CommonHelper::tsToDt($_extension->start_time);
                $date = date('Y-m-d', strtotime($dateTime));
                $talkTime = ((!empty($_extension->ans_time) && !empty($_extension->end_time)) ? date('H:i:s', strtotime($_extension->end_time) - strtotime($_extension->ans_time)) : '00:00:00');
                if ($date == date('Y-m-d')) {
                    $callDate = "Today";
                } elseif($date == date('Y-m-d', strtotime('-1 day'))) {
                    $callDate = 'Yesterday';
                } elseif($date == date('Y-m-d', strtotime('+1 day'))) {
                    $callDate = 'Tomorrow';
                } else {
                    $callDate = date('Y-m-d', strtotime($dateTime));
                }
                $html .= '
                <li>
                    <div class="collapsible-header">
                        <div class="call-lists">';
                            if($_extension->call_type == 'video') {
                                $html .= '<img src = "'.Url::base(true) . '/theme/assets/images/missed-videocall-icon.png" />';
                            }else{
                                $html .= '<img src="'.Url::base(true) . '/theme/assets/images/missed-call-icon.png" />';
                            }
                             $html .= '<div>
                                <p class="caller-name">'.$_extension->from_number.'</p>
                                <p class="caller-time">'.date('h:i A', strtotime($dateTime)).'</p>
                            </div>
                             <div class="ml-auto">
                                <div class="caller-date">'.$callDate.'</div>
                                <p class="mb-0 call-time">'.$talkTime.'</p>
                           </div>
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <div class="call-opiton">
                             <i class="material-icons dial-pad-open" id="audioCall" data-number="'.$_extension->from_number.'">local_phone</i>';
                        if($videoCall){
                            $html .= '<i class="material-icons videocall-enable" id="videoCall" data-number="'.$_extension->from_number.'">videocam</i>';
                        }else{
                            $html .= '<i class="material-icons cursor-auto">videocam_off</i>';
                        }
                        $html .= '</div>
                    </div>
                </li>';
            }
        }
        return $html;
    }

    public static function getIncomingCall(){
        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);

        $videoCall = $callSetting_data->ecs_video_calling;
        $html = '';
        $extension = ExtensionCdr::find()
            ->andWhere(['!=', 'from_number', ''])
            ->andWhere(['!=', 'to_number', ''])
            ->andWhere(['to_number' => Yii::$app->user->identity->em_extension_number])
            ->andWhere(['IS NOT', 'ans_time', null])
            ->orderBy('start_time DESC')->all();
        if(!empty($extension)) {
            foreach ($extension as $_extension) {
                $callDate = '';
                $dateTime = CommonHelper::tsToDt($_extension->start_time);
                $date = date('Y-m-d', strtotime($dateTime));
                $talkTime = ((!empty($_extension->ans_time) && !empty($_extension->end_time)) ? date('H:i:s', strtotime($_extension->end_time) - strtotime($_extension->ans_time)) : '00:00:00');
                if ($date == date('Y-m-d')) {
                    $callDate = "Today";
                } elseif($date == date('Y-m-d', strtotime('-1 day'))) {
                    $callDate = 'Yesterday';
                } elseif($date == date('Y-m-d', strtotime('+1 day'))) {
                    $callDate = 'Tomorrow';
                } else {
                    $callDate = date('Y-m-d', strtotime($dateTime));
                }
                $html .= '
                <li>
                    <div class="collapsible-header">
                        <div class="call-lists">';
                           if($_extension->call_type == 'video') {
                                $html .= '<img src = "'.Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png" />';
                            }else{
                                $html .= '<img src="'.Url::base(true) . '/theme/assets/images/incoming-call-icon.png" />';
                            }
                             $html .= '<div>
                                <p class="caller-name">'.$_extension->from_number.'</p>
                                <p class="caller-time">'.date('h:i A', strtotime($dateTime)).'</p>
                            </div>
                            <div class="ml-auto">
                                <div class="caller-date">'.$callDate.'</div>
                                <p class="mb-0 call-time">'.$talkTime.'</p>
                           </div>
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <div class="call-opiton">
                             <i class="material-icons dial-pad-open" id="audioCall" data-number="'.$_extension->from_number.'">local_phone</i>';
                        if($videoCall){
                            $html .= '<i class="material-icons videocall-enable" id="videoCall" data-number="'.$_extension->from_number.'">videocam</i>';
                        }else{
                            $html .= '<i class="material-icons cursor-auto">videocam_off</i>';
                        }
                        $html .= '</div>
                    </div>
                </li>';
            }
        }
        return $html;
    }

    public static function getOutgoingCall(){
        $html = '';

        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);

        $videoCall = $callSetting_data->ecs_video_calling;
        $extension = ExtensionCdr::find()
            ->andWhere(['!=', 'from_number', ''])
            ->andWhere(['!=', 'to_number', ''])
            ->where(['from_number' => Yii::$app->user->identity->em_extension_number])
            ->orderBy('start_time DESC')->all();
        if(!empty($extension)) {
            foreach ($extension as $_extension) {
                $callDate = '';
                $dateTime = CommonHelper::tsToDt($_extension->start_time);
                $date = date('Y-m-d', strtotime($dateTime));
                $talkTime = ((!empty($_extension->ans_time) && !empty($_extension->end_time)) ? date('H:i:s', strtotime($_extension->end_time) - strtotime($_extension->ans_time)) : '00:00:00');
                if ($date == date('Y-m-d')) {
                    $callDate = "Today";
                } elseif($date == date('Y-m-d', strtotime('-1 day'))) {
                    $callDate = 'Yesterday';
                } elseif($date == date('Y-m-d', strtotime('+1 day'))) {
                    $callDate = 'Tomorrow';
                } else {
                    $callDate = date('Y-m-d', strtotime($dateTime));
                }
                $html .= '
                <li>
                    <div class="collapsible-header">
                        <div class="call-lists">';
                            if($_extension->call_type == 'video') {
                                $html .= '<img src = "'.Url::base(true) . '/theme/assets/images/outgoing-videocall-icon.png" />';
                            }else{
                                $html .= '<img src = "'.Url::base(true) . '/theme/assets/images/outgoing-call.png" />';
                            }
                             $html .= '<div>
                                <p class="caller-name">'.$_extension->to_number.'</p>
                                <p class="caller-time">'.date('h:i A', strtotime($dateTime)).'</p>
                            </div>
                            <div class="ml-auto">
                                <div class="caller-date">'.$callDate.'</div>
                                <p class="mb-0 call-time">'.$talkTime.'</p>
                           </div>
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <div class="call-opiton">
                             <i class="material-icons dial-pad-open" id="audioCall" data-number="'.$_extension->to_number.'">local_phone</i>';
                             if($videoCall){
                                 $html .= '<i class="material-icons videocall-enable" id="videoCall" data-number="'.$_extension->to_number.'">videocam</i>';
                             }else{
                                 $html .= '<i class="material-icons cursor-auto">videocam_off</i>';
                             }
                       $html .= '</div>
                    </div>
                </li>';
            }
        }
        return $html;
    }
}
