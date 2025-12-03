<?php

namespace app\modules\ecosmob\fail2ban\models;

use app\modules\ecosmob\fail2ban\Fail2banModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_ip_table".
 *
 * @property int $it_id
 * @property string $it_source
 * @property string $it_destination
 * @property int $it_port
 * @property string $it_protocol
 * @property string $it_service
 * @property string $it_action
 * @property string $it_direction
 */
class Cdr extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_bw_rules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Fail2banModule::t('fail2ban', 'id'),
            'bw_rule_value' => Fail2banModule::t('fail2ban', 'bw_rule_value'),
            'ports' => Fail2banModule::t('fail2ban', 'ports'),
            'protocol' => Fail2banModule::t('fail2ban', 'protocol'),
            'jail' => Fail2banModule::t('fail2ban', 'jail'),
            'hostname' => Fail2banModule::t('fail2ban', 'hostname'),
            'country' => Fail2banModule::t('fail2ban', 'country'),
            'bw_added_by' => Fail2banModule::t('fail2ban', 'bw_added_by'),
        ];
    }
}
