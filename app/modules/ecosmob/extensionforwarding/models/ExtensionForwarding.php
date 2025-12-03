<?php

namespace app\modules\ecosmob\extensionforwarding\models;
use app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule;

use Yii;

/**
 * This is the model class for table "ct_extension_forwarding".
 *
 * @property int $ef_id Auto-increment Id
 * @property string $ef_extension Extension number
 * @property string $ef_unconditional_type
 * @property string ef_unconditional_num Un conditional forwatding: 0 -> diabled,1 Enables
 * @property string $ef_holiday_type
 * @property string $ef_holiday
 * @property string $ef_holiday_num
 * @property string $ef_weekoff_type
 * @property string $ef_weekoff
 * @property string $ef_weekoff_num
 * @property string $ef_shift_type
 * @property string $ef_shift
 * @property string $ef_shift_num
 * @property string $ef_universal_type
 * @property string $ef_universal Universal forward.Values,NULL(Disabled) or number to forward
 * @property string $ef_no_answer_type
 * @property string $ef_no_answer No Answer.Values NUMBER:RINGTIMEOUT,NULL(Disabled) or number to forward
 * @property string $ef_busy_type
 * @property string $ef_busy Busy.Values,NULL(Disabled) or number to forward
 * @property string $ef_unavailable_type
 * @property string $ef_unavailable Unavailable.Values,NULL(Disabled) or number to forward
 * @property string $ef_call_return It is contains caller id of last recieved call
 * @property string $ef_call_redial It contains last dialed number
 * @property string $ef_universal_num
 * @property string $ef_no_answer_num
 * @property string $ef_busy_num
 * @property string $ef_unavailable_num
 * @property string $ecs_call_redial
 */
class ExtensionForwarding extends \yii\db\ActiveRecord
{
    public $internal_type;
    public $external_type;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_extension_forwarding';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ef_extension'], 'required'],
            [['ef_extension'], 'unique'],
            [
                ['ef_extension',
                    'ef_unconditional_type',
                    'ef_holiday', 'ef_holiday_type',
                    'ef_weekoff', 'ef_weekoff_type',
                    'ef_shift', 'ef_shift_type',
                    'ef_universal_type',
                    'ef_no_answer_type',
                    'ef_busy_type',
                    'ef_unavailable_type'
                ], 'safe'
            ],
            [['ef_extension', 'ef_unconditional_num', 'ef_holiday_num', 'ef_weekoff_num', 'ef_shift_num', 'ef_universal_num', 'ef_no_answer_num', 'ef_busy_num', 'ef_unavailable_num'], 'integer'],
            /*[['ef_extension', 'ef_unconditional_num', 'ef_holiday_num', 'ef_weekoff_num', 'ef_shift_num', 'ef_universal_num', 'ef_no_answer_num', 'ef_busy_num', 'ef_unavailable_num'], 'string', 'min'=>4, 'max'=>15],*/
            /*['ef_extension', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_holiday_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_weekoff_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_shift_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_universal_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_no_answer_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_busy_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],
            ['ef_unavailable_num', 'match', 'pattern'=>'/^[+0-9]{4,15}$/', 'message'=>'Its contain number and + sign only with min 4 number & max 15'],*/

            [
                'ef_unconditional_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_unconditional_type == 'INTERNAL' || $model->ef_unconditional_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_unconditional_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_unconditional_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ef_holiday_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_holiday_type == 'INTERNAL' || $model->ef_holiday_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                           return ($('#extensionforwarding-ef_holiday_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_holiday_type').val() == 'EXTERNAL');
                       }",
                'message' => "This field is required."
            ],

            [
                'ef_weekoff_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_weekoff_type == 'INTERNAL' || $model->ef_weekoff_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_weekoff_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_weekoff_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ef_shift_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_shift_type == 'INTERNAL' || $model->ef_shift_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_shift_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_shift_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ef_universal_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_universal_type == 'INTERNAL' || $model->ef_universal_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_universal_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_universal_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ef_no_answer_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_no_answer_type == 'INTERNAL' || $model->ef_no_answer_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_no_answer_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_no_answer_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],
            [
                'ef_unavailable_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_unavailable_type == 'INTERNAL' || $model->ef_unavailable_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_unavailable_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_unavailable_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                'ef_busy_num',
                'required',
                'when'=>function ($model) {
                    return ($model->ef_busy_type == 'INTERNAL' || $model->ef_busy_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_busy_type').val() == 'INTERNAL' || $('#extensionforwarding-ef_busy_type').val() == 'EXTERNAL');
                    }",
                'message' => "This field is required."
            ],

            [
                ['ef_unconditional_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_unconditional_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_unconditional_type').val() == 'EXTERNAL');
                    }"
            ],



            [
                ['ef_holiday_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_holiday_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_holiday_type').val() == 'EXTERNAL');
                    }"
            ],


            [
                ['ef_weekoff_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_weekoff_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_weekoff_type').val() == 'EXTERNAL');
                    }"
            ],



            [
                ['ef_shift_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_shift_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_shift_type').val() == 'EXTERNAL');
                    }"
            ],



            [
                ['ef_universal_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_universal_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_universal_type').val() == 'EXTERNAL');
                    }"
            ],



            [
                ['ef_no_answer_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_no_answer_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_no_answer_type').val() == 'EXTERNAL');
                    }"
            ],


            [
                ['ef_busy_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_busy_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_busy_type').val() == 'EXTERNAL');
                    }"
            ],


            [
                ['ef_unavailable_num'],
                'match',
                'pattern'=>'/^[+0-9]{4,15}$/',
                'message'=>'Its contain number and + sign only with min 4 number & max 15',
                'when'=>function ($model) {
                    return ($model->ef_unavailable_type == 'EXTERNAL');
                }, 'whenClient'=>"function (attribute, value) {
                        return ($('#extensionforwarding-ef_unavailable_type').val() == 'EXTERNAL');
                    }"
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ef_id'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_id'),
            'ef_extension'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_extension'),
            'ef_unconditional_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_unconditional_type'),
            'ef_unconditional_num'=>Yii::t('app', 'ef_unconditional_num'),
            'ef_holiday_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_holiday_type'),
            'ef_holiday'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_holiday'),
            'ef_holiday_num'=>Yii::t('app', 'ef_holiday_num'),
            'ef_weekoff_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_weekoff_type'),
            'ef_weekoff'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_weekoff'),
            'ef_weekoff_num'=>Yii::t('app', 'ef_weekoff_num'),
            'ef_shift_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_shift_type'),
            'ef_shift'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_shift'),
            'ef_shift_num'=>Yii::t('app', 'ef_shift_num'),
            'ef_universal_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_universal_type'),
            'ef_universal_num'=>Yii::t('app', 'ef_universal_num'),
            'ef_no_answer_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_no_answer_type'),
            'ef_no_answer_num'=>Yii::t('app', 'ef_no_answer_num'),
            'ef_busy_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_busy_type'),
            'ef_busy_num'=>Yii::t('app', 'ef_busy_num'),
            'ef_unavailable_type'=>ExtensionForwardingModule::t('extensionforwarding', 'ef_unavailable_type'),
            'ef_unavailable_num'=>Yii::t('app', 'ef_unavailable_num'),
        ];
    }
}
