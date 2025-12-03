<?php

namespace app\modules\ecosmob\timeclockreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Yii;
use yii\db\ActiveRecord;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;

/**
 * This is the model class for table "users_activity_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_time
 * @property string $logout_time
 * @property string $created_at
 */
class TimeClockReport extends ActiveRecord
{
    public $total_log_hours;
    public $total_break_time;
    public $total_breaks;
    public $agent;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_activity_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['login_time', 'logout_time', 'created_at', 'campaign_name', 'total_log_hours', 'total_break_time', 'total_breaks', 'agent'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agent' => 'User',
            'user_id' => 'User',
            'login_time' => 'Logged In Time',
            'logout_time' => 'Logout Time',
            'total_log_hours' => 'Total Logged In Hours',
            'total_break_time' => 'Total Break Hours',
            'total_breaks' => 'Total Breaks'
        ];
    }

    public static function getBreakData($userId, $from, $to){
        $break = BreakReasonMapping::find()
            ->select(['count(id) as total_breaks', 'SUM(TIMESTAMPDIFF(SECOND, login_time, logout_time)) as break_time'])
            ->andWhere(['user_id' => $userId])
            ->andWhere(['>=', 'in_time',  $from])
            ->andWhere(['>=', 'in_time',  $to])
            ->one();
    }

    public static function getTotalLogHours($from, $to, $userId){
        $sum = 0;
        $user = UsersActivityLog::find()
            ->select(['login_time', 'logout_time'])
            ->andWhere(['>=', 'DATE(login_time)', $from])
            ->andWhere(['<=', 'DATE(login_time)', $to])
            ->andWhere(['user_id' => $userId])
            ->groupBy(['login_time'])
            ->all();
        if(!empty($user)){
            foreach($user as $_user){
                $sum += strtotime($_user->logout_time == '0000-00-00 00:00:00' ? CommonHelper::tsToDt(date('Y-m-d H:i:s')) : CommonHelper::tsToDt($_user->logout_time)) - strtotime(CommonHelper::tsToDt($_user->login_time));
            }
        }
        return $sum;
    }
}
